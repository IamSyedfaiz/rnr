<?php
namespace App\Http\Controllers\backend;

// namespace the42coders\Workflows\Http\Controllers;

use App\Models\backend\Application;
use App\Models\backend\Notification;
use App\Models\backend\TriggerMail;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use the42coders\Workflows\Loggers\WorkflowLog;
use the42coders\Workflows\Tasks\Task;
use the42coders\Workflows\Triggers\ReRunTrigger;
use the42coders\Workflows\Triggers\Trigger;
//use App\Http\Controllers\Controller;
use the42coders\Workflows\Workflow;
use App\Models\backend\Formdata;
use App\Models\User;
use App\Models\backend\Group;
use Illuminate\Support\Facades\Mail;

class CustomWorkflowController extends Controller
{
    public function index()
    {
        $workflows = Workflow::paginate(25);

        return view('workflows::index', ['workflows' => $workflows]);
    }

    public function show($id)
    {
        $existingWorkflow = Workflow::where('application_id', $id)->first();
        $application = Application::find($id);

        if ($existingWorkflow) {
            return view('workflows::diagram', ['workflow' => $existingWorkflow, 'id' => $id]);
        } else {
            $workflow = new Workflow();
            $workflow->name = $application->name;
            $workflow->application_id = $id;
            $workflow->save();
            return view('workflows::diagram', ['workflow' => $workflow, 'id' => $id]);
        }

        // return view('workflows::diagram', ['workflow' => $workflow]);
    }

    public function create()
    {
        return view('workflows::create');
    }

    public function store(Request $request)
    {
        dd($request->all());
        $workflow = Workflow::create($request->all());

        return redirect(route('workflow.show', ['workflow' => $workflow]));
    }

    public function edit($id)
    {
        $workflow = Workflow::find($id);

        return view('workflows::edit', [
            'workflow' => $workflow,
        ]);
    }

    public function update(Request $request, $id)
    {
        $workflow = Workflow::find($id);

        $workflow->update($request->all());

        return redirect(route('workflow.index'));
    }

    /**
     * Deletes the Workflow and over cascading also the Tasks, TaskLogs, WorkflowLogs and Triggers.
     *
     * @param  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete($id)
    {
        $workflow = Workflow::find($id);

        $workflow->delete();

        return redirect(route('workflow.index'));
    }

    public function addTask($id, Request $request)
    {
        $workflow = Workflow::find($id);
        if ($request->data['type'] == 'trigger') {
            return [
                'task' => '',
            ];
        }
        $task = Task::where('workflow_id', $workflow->id)
            ->where('node_id', $request->id)
            ->first();

        if (!empty ($task)) {
            $task->pos_x = $request->pos_x;
            $task->pos_y = $request->pos_y;
            $task->save();

            return ['task' => $task];
        }

        if (array_key_exists($request->name, config('workflows.tasks'))) {
            $task = config('workflows.tasks')[$request->name]::create([
                'type' => config('workflows.tasks')[$request->name],
                'workflow_id' => $workflow->id,
                'name' => $request->name,
                'data_fields' => null,
                'node_id' => $request->id,
                'pos_x' => $request->pos_x,
                'pos_y' => $request->pos_y,
            ]);
        }

        return [
            'task' => $task,
            'node_id' => $request->id,
        ];
    }

    public function addTrigger($id, Request $request)
    {
        $workflow = Workflow::find($id);

        if (array_key_exists($request->name, config('workflows.triggers.types'))) {
            $trigger = config('workflows.triggers.types')[$request->name]::create([
                'type' => config('workflows.triggers.types')[$request->name],
                'workflow_id' => $workflow->id,
                'name' => $request->name,
                'data_fields' => null,
                'pos_x' => $request->pos_x,
                'pos_y' => $request->pos_y,
            ]);
        }

        return [
            'trigger' => $trigger,
            'node_id' => $request->id,
        ];
    }

    public function changeConditions($id, Request $request)
    {
        $workflow = Workflow::find($id);

        if ($request->type == 'task') {
            $element = $workflow->tasks->find($request->id);
        }

        if ($request->type == 'trigger') {
            $element = $workflow->triggers->find($request->id);
        }

        $element->conditions = $request->data;
        $element->save();

        return $element;
    }

    public function changeValues($id, Request $request)
    {
        $workflow = Workflow::find($id);

        if ($request->type == 'task') {
            $element = $workflow->tasks->find($request->id);
        }

        if ($request->type == 'trigger') {
            $element = $workflow->triggers->find($request->id);
        }

        $data = [];

        foreach ($request->data as $key => $value) {
            $path = explode('->', $key);
            $data[$path[0]][$path[1]] = $value;
        }
        $element->data_fields = $data;
        $element->save();

        return $element;
    }

    public function updateNodePosition($id, Request $request)
    {
        $element = $this->getElementByNode($id, $request->node);

        $element->pos_x = $request->node['pos_x'];
        $element->pos_y = $request->node['pos_y'];
        $element->save();

        return ['status' => 'success'];
    }

    public function getElementByNode($workflow_id, $node)
    {
        if ($node['data']['type'] == 'task') {
            $element = Task::where('workflow_id', $workflow_id)
                ->where('id', $node['data']['task_id'])
                ->first();
        }

        if ($node['data']['type'] == 'trigger') {
            $element = Trigger::where('workflow_id', $workflow_id)
                ->where('id', $node['data']['trigger_id'])
                ->first();
        }

        return $element;
    }

    public function addConnection($id, Request $request)
    {
        $workflow = Workflow::find($id);

        if ($request->parent_element['data']['type'] == 'trigger') {
            $parentElement = Trigger::where('workflow_id', $workflow->id)
                ->where('id', $request->parent_element['data']['trigger_id'])
                ->first();
        }
        if ($request->parent_element['data']['type'] == 'task') {
            $parentElement = Task::where('workflow_id', $workflow->id)
                ->where('id', $request->parent_element['data']['task_id'])
                ->first();
        }
        if ($request->child_element['data']['type'] == 'trigger') {
            $childElement = Trigger::where('workflow_id', $workflow->id)
                ->where('id', $request->child_element['data']['trigger_id'])
                ->first();
        }
        if ($request->child_element['data']['type'] == 'task') {
            $childElement = Task::where('workflow_id', $workflow->id)
                ->where('id', $request->child_element['data']['task_id'])
                ->first();
        }

        $childElement->parentable_id = $parentElement->id;
        $childElement->parentable_type = get_class($parentElement);

        $childElement->save();

        return ['status' => 'success'];
    }

    public function removeConnection($id, Request $request)
    {
        $workflow = Workflow::find($id);

        $childTask = Task::where('workflow_id', $workflow->id)
            ->where('node_id', $request->input_id)
            ->first();

        $childTask->parentable_id = 0;
        $childTask->parentable_type = null;
        $childTask->save();

        return ['status' => 'success'];
    }

    public function removeTask($id, Request $request)
    {
        $workflow = Workflow::find($id);

        $element = $this->getElementByNode($id, $request->node);

        $element->delete();

        return [
            'status' => 'success',
        ];
    }

    public function getElementSettings($id, Request $request)
    {
        $workflow = Workflow::find($id);

        if ($request->type == 'task') {
            $element = Task::where('workflow_id', $workflow->id)
                ->where('id', $request->element_id)
                ->first();
        }
        if ($request->type == 'trigger') {
            $element = Trigger::where('workflow_id', $workflow->id)
                ->where('id', $request->element_id)
                ->first();
        }
        if ($element->name == 'Start') {
            logger('yes i am in');
            return view('workflows::custom.start', [
                'element' => $workflow,
            ]);
        } elseif ($element->name == 'EvaluateContent') {
            logger('yes i am in');
            return view('workflows::custom.evaluatecontentedit', [
                'element' => $workflow,
            ]);
        }
        return view('workflows::layouts.settings_overlay', [
            'element' => $element,
        ]);
    }

    public function getElementConditions($id, Request $request)
    {
        $workflow = Workflow::find($id);

        if ($request->type == 'task') {
            $element = Task::where('workflow_id', $workflow->id)
                ->where('id', $request->element_id)
                ->first();
        }
        if ($request->type == 'trigger') {
            $element = Trigger::where('workflow_id', $workflow->id)
                ->where('id', $request->element_id)
                ->first();
        }

        $filter = [];

        foreach (config('workflows.data_resources') as $resourceName => $resourceClass) {
            $filter[$resourceName] = $resourceClass::getValues($element, null, null);
        }

        return view('workflows::layouts.conditions_overlay', [
            'element' => $element,
            'conditions' => $element->conditions,
            'allFilters' => $filter,
        ]);
    }

    public function loadResourceIntelligence($id, Request $request)
    {
        $workflow = Workflow::find($id);

        if ($request->type == 'task') {
            $element = Task::where('workflow_id', $workflow->id)
                ->where('id', $request->element_id)
                ->first();
        }
        if ($request->type == 'trigger') {
            $element = Trigger::where('workflow_id', $workflow->id)
                ->where('id', $request->element_id)
                ->first();
        }

        if (in_array($request->resource, config('workflows.data_resources'))) {
            $className = $request->resource ?? 'the42coders\\Workflows\\DataBuses\\ValueResource';
            $resource = new $className();
            $html = $resource->loadResourceIntelligence($element, $request->value, $request->field_name);
        }

        return response()->json([
            'html' => $html,
            'id' => $request->field_name,
        ]);
    }

    public function getLogs($id)
    {
        $workflow = Workflow::find($id);

        $workflowLogs = $workflow->logs()->orderBy('start', 'desc')->get();
        //TODO: get Pagination working

        return view('workflows::layouts.logs_overlay', [
            'workflowLogs' => $workflowLogs,
        ]);
    }

    public function reRun($workflowLogId)
    {
        $log = WorkflowLog::find($workflowLogId);

        ReRunTrigger::startWorkflow($log);

        return [
            'status' => 'started',
        ];
    }

    public function triggerButton(Request $request, $triggerId)
    {
        $trigger = Trigger::findOrFail($triggerId);
        $className = $request->model_class;
        $resource = new $className();

        $model = $resource->find($request->model_id);

        $trigger->start($model, []);

        return redirect()->back()->with('success', 'Button Triggered a Workflow');
    }
    public function triggerButtonShow(Request $request, $triggerId)
    {

        // $trigger = Trigger::where('workflow_id', $triggerId)
        //     ->first();
        // logger($trigger);
        $tasks = Task::where('workflow_id', $triggerId)->where('parentable_id', $trigger->id)->get();
        logger($tasks);
        dd(1);
        foreach ($tasks as $task) {
            if ($task->parentable_type == 'the42coders\Workflows\Triggers\ButtonTrigger') {
                $parentTaskId = $task->parentableName->id ?? '';
                $taskParent = Task::where('parentable_id', $parentTaskId)->get();
                logger('taskParent');
                logger($taskParent);
            }

            $childrenTasksIds = [];
            foreach ($task->childrenName as $childTask) {
                $childrenTasksIds[] = $childTask->id;
            }

            // logger('parentTaskId');
            // logger($parentTaskId);
            // logger('========');
            // logger('childrenTasksIds');
            // logger($childrenTasksIds);
            // if ($parentTaskId) {
            //     $tasksGet = Task::find($parentTaskId);
            //     if ($tasksGet->name == "SendNotification") {
            //         $triggerMail = TriggerMail::find($task->workflow_id);

            //         $notification = Notification::where('active', 'Y')->where('recurring', 'instantly')->where('id', $triggerMail->notification_id)->first();

            //         logger($notification);

            //         // $inputString = $notification->advanced_operator_logic;
            //         // $allFilterCriterias = $notification->filterCriterias;

            //         $selectedGroups = [];
            //         if ($notification->group_list != 'null') {
            //             $groupIds = json_decode($notification->group_list);

            //             if ($groupIds) {
            //                 foreach ($groupIds as $groupId) {
            //                     $group = Group::find($groupId);

            //                     if ($group) {
            //                         $selectedGroups[] = $group->userids;
            //                     }
            //                 }
            //             }
            //         }
            //         $userGroups = [];
            //         foreach ($selectedGroups as $groupUserIds) {
            //             // Decode the JSON string to an array
            //             $groupUserIdsArray = json_decode($groupUserIds, true);

            //             // Check if $groupUserIdsArray is an array
            //             if (!is_array($groupUserIdsArray)) {
            //                 $this->error('Invalid data for groupUserIds: ' . $groupUserIds);
            //                 continue; // Skip to the next iteration if data is invalid
            //             }

            //             foreach ($groupUserIdsArray as $userId) {
            //                 // Check if $userId is a valid integer
            //                 if (!is_numeric($userId) || intval($userId) <= 0) {
            //                     $this->error("Invalid user ID: $userId");
            //                     continue; // Skip to the next iteration if user ID is invalid
            //                 }

            //                 // Find the user by ID
            //                 $user = User::find(intval($userId));

            //                 if ($user) {
            //                     $userGroups[] = $user->email;
            //                 } else {
            //                     $this->error("User not found for ID: $userId");
            //                 }
            //             }
            //         }

            //         $selectedUsers = [];
            //         if ($notification->user_list != 'null') {
            //             $UserIds = json_decode($notification->user_list);

            //             if ($UserIds) {
            //                 foreach ($UserIds as $UserId) {
            //                     $User = User::find($UserId);

            //                     if ($User) {
            //                         $selectedUsers[] = $User->email;
            //                     }
            //                 }
            //             }
            //         }
            //         $template = $notification->body;
            //         $Formdata01 = Formdata::where('application_id', $notification->application_id)->get();

            //         $parsedData = collect(json_decode($Formdata01, true));
            //         $replacedTemplates = [];

            //         $parsedData->each(function ($entry) use ($template, &$replacedTemplates) {
            //             $data = json_decode($entry['data'], true);

            //             // Replace placeholders with values
            //             $replacedTemplate = $template;

            //             foreach ($data as $key => $value) {
            //                 $placeholder = "[field:$key]";
            //                 $replacedTemplate = str_replace($placeholder, $value, $replacedTemplate);
            //             }

            //             $replacedTemplates[] = $replacedTemplate;
            //             logger($replacedTemplate);
            //         });

            //         // dd($replacedTemplates);
            //         $replaceddata['body'] = $replacedTemplates;

            //         // $data['body'] = $notification->body;
            //         if ($userGroups) {
            //             foreach ($userGroups as $recipient) {
            //                 Mail::send('email.loginmail', @$replaceddata, function ($msg) use ($recipient, $notification) {
            //                     $msg->from(env('MAIL_FROM_ADDRESS'));
            //                     $msg->to($recipient, env('MAIL_FROM_NAME'));
            //                     $msg->subject($notification->subject);
            //                 });
            //             }
            //         }
            //         if ($selectedUsers) {
            //             foreach ($selectedUsers as $recipient) {
            //                 Mail::send('email.loginmail', @$replaceddata, function ($msg) use ($recipient, $notification) {
            //                     $msg->from(env('MAIL_FROM_ADDRESS'));
            //                     $msg->to($recipient, env('MAIL_FROM_NAME'));
            //                     $msg->subject($notification->subject);
            //                 });
            //             }
            //         }
            //         logger('send mail');


            //         logger($triggerMail);
            //         logger($userGroups);
            //         logger($parsedData);
            //         logger($selectedUsers);
            //         logger('triggerMail');

            //     } else {
            //         logger('go');

            //     }
            // }



        }

        // dd($tasks);

        return redirect()->back()->with('success', 'Button Triggered a Workflow');
    }
    public function saveMail(Request $request)
    {

        // If validation passes, retrieve the data from the request
        $workflowId = $request->input('workflow_id');
        $applicationId = $request->input('application_id');
        $notificationId = $request->input('notification');

        // Save the data to the database
        $triggerMail = new TriggerMail();
        $triggerMail->workflow_id = $workflowId;
        $triggerMail->notification_id = $notificationId;
        $triggerMail->application_id = $applicationId;
        $triggerMail->save();

        return redirect()->back()->with('success', 'Data saved successfully');
    }
}