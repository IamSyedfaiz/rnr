<?php

namespace App\Http\Controllers\backend;

// namespace the42coders\Workflows\Http\Controllers;

use App\Models\backend\Application;
use App\Models\backend\EvaluateContent;
use App\Models\backend\EvaluateRule;
use App\Models\backend\Notification;
use App\Models\backend\Transition;
use App\Models\backend\TriggerMail;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use the42coders\Workflows\Loggers\WorkflowLog;
use the42coders\Workflows\Tasks\Task;
use the42coders\Workflows\Triggers\ReRunTrigger;
use the42coders\Workflows\Triggers\Trigger;
use the42coders\Workflows\Workflow;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\backend\Field;
use App\Models\backend\Formdata;
use App\Models\backend\Group;
use App\Models\backend\MyLog;
use App\Models\backend\UpdateContent;
use App\Models\backend\UserAction;
use App\Models\User;
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

        if (!empty($task)) {
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
        $notifications = Notification::all();
        $fields = Field::where('application_id', $workflow->application_id)
            ->where('status', 1)
            ->get();

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
            // logger('yes i am in');
            return view('workflows::custom.start', [
                'element' => $workflow,
            ]);
        } elseif ($element->name == 'EvaluateContent') {
            // logger('yes i am in');
            // logger($workflow);
            // logger($fields);
            // $evaluateContent = EvaluateContent::where('Workflow_id', $workflow->id)->get();
            // logger($evaluateContent);

            return view('workflows::custom.evaluatecontent', [
                'element' => $workflow,
                'notifications' => $notifications,
                'fields' => $fields,
            ]);
        } elseif ($element->name == 'SendNotification') {
            // logger('yes i am in');
            return view('workflows::custom.sendnotification', [
                'element' => $workflow,
                'notifications' => $notifications,
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
        $task = Task::where('workflow_id', $triggerId)->first();
        $parentTaskId = $task->parentableName->id ?? '';

        if ($parentTaskId) {
            $taskParent = Task::find($parentTaskId);
            if ($taskParent !== null && $taskParent->name == 'EvaluateContent') {
                logger('=======');
                logger('EvaluateContent');
                logger($taskParent->id);
                $getValue = $this->TriggerEvaluateContent($taskParent->id);

                // logger('getValue');
                // logger($getValue);
                $childrenTasksIds = [];
                $childrenTasksNames = [];
                foreach ($taskParent->childrenName as $childTask) {
                    $childrenTasksIds[] = $childTask->id;
                    $childrenTasksNames[] = $childTask->name;
                }
                logger('childrenTasksIds');
                logger($childrenTasksIds);
                // // logger('childrenTasksNames');
                // // logger($childrenTasksNames);
                // if ($getValue) {
                //     $this->triggerButtonChildren($childrenTasksIds[0]);
                // } else {
                //     $this->triggerButtonChildren($childrenTasksIds[1]);
                // }
            } elseif ($taskParent !== null && $taskParent->name == 'SendNotification') {
                logger('=======');
                logger('SendNotification');
                $this->TriggerSendMail($taskParent->id);
                $this->triggerButtonChildren($taskParent->id);
            } elseif ($taskParent !== null && $taskParent->name == 'UpdateContent') {
                logger('=======');
                logger('UpdateContent');
                $this->triggerButtonChildren($taskParent->id);
            } elseif ($taskParent !== null && $taskParent->name == 'UserAction') {
                logger('UpdateContent');
                $this->triggerButtonChildren($taskParent->id);
            } else {
                return redirect()->back()->with('error', 'not found');
            }
        } else {
            if ($task !== null && $task->name == 'EvaluateContent') {
                logger('=======');
                logger('EvaluateContent');
                // logger($task->id);
                $getValue = $this->TriggerEvaluateContent($task->id);
                $childrenTasksIds = [];
                $childrenTasksNames = [];
                if ($task->childrenName) {
                    foreach ($task->childrenName as $childTask) {
                        $childrenTasksIds[] = $childTask->id;
                        $childrenTasksNames[] = $childTask->name;
                    }
                }
                logger('childrenTasksIds');
                logger($childrenTasksIds);
                logger('childrenTasksNames');
                logger($childrenTasksNames);

                logger('getValue');
                logger($getValue);
                if ($getValue) {
                    $this->triggerButtonChildren($childrenTasksIds[0]);
                } else {
                    $this->triggerButtonChildren($childrenTasksIds[1]);
                }
            } elseif ($task !== null && $task->name == 'SendNotification') {
                logger('=======');
                logger('SendNotification');
                $this->TriggerSendMail($task->id);
                $this->triggerButtonChildren($task->id);
            } elseif ($task !== null && $task->name == 'UpdateContent') {
                logger('=======');
                logger('UpdateContent');
                $this->TriggerUpdateContent($task->id);

                $this->triggerButtonChildren($task->id);
            } elseif ($task !== null && $task->name == 'UserAction') {
                logger('=======');
                logger('UpdateContent');
                $this->triggerButtonChildren($task->id);
            } else {
                return redirect()->back()->with('error', 'not found');
            }
        }
        return redirect()->back()->with('success', 'Button Triggered a Workflow');
    }

    public function triggerButtonChildren($triggerId)
    {
        logger('triggerButtonChildren');

        $tasks = Task::find($triggerId);
        logger($tasks);

        $childrenTasksIds = [];
        $childrenTasksNames = [];
        foreach ($tasks->childrenName as $childTask) {
            $childrenTasksIds[] = $childTask->id;
            $childrenTasksNames[] = $childTask->name;
        }
        // logger('childrenTasksNames');
        // logger($childrenTasksNames);
        logger('childrenTasksIds');
        logger($childrenTasksIds);

        // if (in_array('SendNotification', $childrenTasksNames)) {
        if ($tasks->name == 'SendNotification') {
            logger('=======');
            logger('SendNotification');

            if ($childrenTasksIds) {
                $this->TriggerSendMail($childrenTasksIds[0]);
                $this->triggerButtonChildren($childrenTasksIds[0]);
            } else {
                $this->TriggerSendMail($tasks->id);
            }
        }
        // if (in_array('EvaluateContent', $childrenTasksNames)) {
        if ($tasks->name == 'EvaluateContent') {
            logger('=======');
            logger('EvaluateContent');
            // $getValue = $this->TriggerEvaluateContent($childrenTasksIds[0]);

            // logger('getValue');
            // logger($getValue);

            // if ($getValue) {
            //     $this->triggerButtonChildren($childrenTasksIds[0]);

            // } else {
            //     $this->triggerButtonChildren($childrenTasksIds[1]);

            // }
        }
        if (in_array('UpdateContent', $childrenTasksNames)) {
            logger('=======');
            logger('UpdateContent');
            $this->triggerButtonChildren($childrenTasksIds[0]);
        }
        if (in_array('UserAction', $childrenTasksNames)) {
            logger('=======');
            logger('UserAction');
            $this->triggerButtonChildren($childrenTasksIds[0]);
        }
        // logger('taskParent triggerButtonChildren');
        return redirect()->back()->with('success', 'Button Triggered a Workflow');
    }
    public function saveMail(Request $request)
    {
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
    public function evaluateContent(Request $request)
    {
        try {
            $id = $request->input('id');
            $rules = [
                'name' => 'nullable',
            ];
            $messages = [
                'name.nullable' => 'The name field is required.',
                'name.unique' => 'The name field must be unique.',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                throw ValidationException::withMessages($validator->errors()->toArray());
            }

            $data = $request->except(['_token', 'field_id', 'filter_operator', 'filter_value']);
            $data['active'] = $request->input('active') == 'Y' ? 'Y' : 'N';
            // dd($data);
            // $evaluateContent = new EvaluateContent();
            $evaluateContent = EvaluateContent::find($id); // If editing, find the existing entity
            if (!$evaluateContent) {
                $evaluateContent = new EvaluateContent();
            }
            $evaluateContent->workflow_id = $request->input('workflow_id');
            $evaluateContent->application_id = $request->input('application_id');
            $evaluateContent->task_id = $request->input('task_id');
            $evaluateContent->name = $request->input('name');
            $evaluateContent->alias = $request->input('alias');
            $evaluateContent->description = $request->input('description');
            $evaluateContent->type = $request->input('type');
            $evaluateContent->advanced_operator_logic = $request->input('advanced_operator_logic');
            $evaluateContent->active = $request->input('active') == 'Y' ? 'Y' : 'N';
            $evaluateContent->save();
            $existingRules = EvaluateRule::where('evaluate_content_id', $evaluateContent->id)->get();
            $filterFields = $request->input('field_id', []);
            $filterOperators = $request->input('filter_operator', []);
            $filterValues = $request->input('filter_value', []);
            if (!empty($filterFields) && !empty($filterValues) && count($filterFields) === count($filterValues)) {
                foreach ($filterFields as $index => $field) {
                    $operator = is_array($filterOperators) ? $filterOperators[$index] ?? null : null;
                    $value = is_array($filterValues) ? $filterValues[$index] ?? null : null;
                    // $operator = $filterOperators[$index] ?? null;
                    // $value = $filterValues[$index] ?? null;

                    // Check if there's an existing rule with the same field
                    $existingRule = $existingRules->where('field_id', $field)->first();

                    if ($existingRule) {
                        // Update the existing rule
                        $existingRule->update([
                            'filter_operator' => $operator,
                            'filter_value' => $value,
                        ]);

                        // Remove the updated rule from the existing rules collection
                        $existingRules = $existingRules->reject(function ($item) use ($existingRule) {
                            return $item->id === $existingRule->id;
                        });
                    } else {
                        // Create a new rule
                        EvaluateRule::create([
                            'evaluate_content_id' => $evaluateContent->id,
                            'field_id' => $field,
                            'filter_operator' => $operator,
                            'filter_value' => $value,
                        ]);
                    }
                }
            }
            Log::channel('custom')->info('Attachment Created by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname);
            return redirect()->back()->with('success', 'Data saved successfully');
        } catch (\Exception $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function UpdateContentStore(Request $request)
    {
        try {
            $attributes = [
                'task_id' => $request->task_id,
            ];
            $values = [
                'application_id' => $request->application_id,
                'workflow_id' => $request->workflow_id,
                'name' => $request->name,
            ];
            $dataForJson = $request->except(['_token', 'application_id', 'workflow_id', 'task_id', 'name']);

            $newData = [$dataForJson['key'] => $dataForJson['value']];
            $values['data'] = json_encode($newData);
            $updateContent = UpdateContent::where('task_id', $request->task_id)->first();

            if ($updateContent) {
                // Decode existing JSON data
                $existingData = json_decode($updateContent->data, true);

                // Merge new data with existing data
                $mergedData = array_merge($existingData, $newData);

                // Encode merged data back to JSON
                $values['data'] = json_encode($mergedData);

                // Update the record with the new values
                $updateContent->update($values);
            } else {
                // If no existing record, just create a new one with the new data
                $values['data'] = json_encode($newData);
                $updateContent = UpdateContent::create(array_merge($attributes, $values));
            }
            // dd($values);
            $updateContent = UpdateContent::updateOrCreate($attributes, $values);

            Log::channel('custom')->info('UpdateContent Created by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname);
            return redirect()->back()->with('success', 'Data saved successfully');
        } catch (\Exception $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function TriggerSendMail($id)
    {
        try {
            logger('TriggerSendMail');
            logger($id);

            $tasksGet = Task::find($id);
            logger($tasksGet);

            if ($tasksGet->name == 'SendNotification') {
                $triggerMail = TriggerMail::find($tasksGet->workflow_id);
                logger('triggerMail mil gayi');
                logger($triggerMail);
                $notification = Notification::where('active', 'Y')
                    ->where('recurring', 'instantly')
                    ->where('id', $triggerMail->notification_id)
                    ->first();
                logger('notification mil gayi');
                logger($notification);
                $selectedGroups = [];
                if ($notification->group_list != 'null') {
                    $groupIds = json_decode($notification->group_list);

                    if ($groupIds) {
                        foreach ($groupIds as $groupId) {
                            $group = Group::find($groupId);

                            if ($group) {
                                $selectedGroups[] = $group->userids;
                            }
                        }
                    }
                }
                $userGroups = [];
                foreach ($selectedGroups as $groupUserIds) {
                    $groupUserIdsArray = json_decode($groupUserIds, true);
                    if (!is_array($groupUserIdsArray)) {
                        $this->error('Invalid data for groupUserIds: ' . $groupUserIds);
                        continue;
                    }
                    foreach ($groupUserIdsArray as $userId) {
                        if (!is_numeric($userId) || intval($userId) <= 0) {
                            $this->error("Invalid user ID: $userId");
                            continue;
                        }
                        $user = User::find(intval($userId));
                        if ($user) {
                            $userGroups[] = $user->email;
                        } else {
                            $this->error("User not found for ID: $userId");
                        }
                    }
                }
                $selectedUsers = [];
                if ($notification->user_list != 'null') {
                    $UserIds = json_decode($notification->user_list);
                    if ($UserIds) {
                        foreach ($UserIds as $UserId) {
                            $User = User::find($UserId);
                            if ($User) {
                                $selectedUsers[] = $User->email;
                            }
                        }
                    }
                }
                $template = $notification->body;
                $Formdata01 = Formdata::where('application_id', $notification->application_id)->get();
                $parsedData = collect(json_decode($Formdata01, true));
                $replacedTemplates = [];
                $parsedData->each(function ($entry) use ($template, &$replacedTemplates) {
                    $data = json_decode($entry['data'], true);
                    $replacedTemplate = $template;
                    foreach ($data as $key => $value) {
                        $placeholder = "[field:$key]";
                        $replacedTemplate = str_replace($placeholder, $value, $replacedTemplate);
                    }
                    $replacedTemplates[] = $replacedTemplate;
                });
                $replaceddata['body'] = $replacedTemplates;
                // $data['body'] = $notification->body;
                if ($userGroups) {
                    foreach ($userGroups as $recipient) {
                        Mail::send('email.loginmail', @$replaceddata, function ($msg) use ($recipient, $notification) {
                            $msg->from(env('MAIL_FROM_ADDRESS'));
                            $msg->to($recipient, env('MAIL_FROM_NAME'));
                            $msg->subject($notification->subject);
                        });
                    }
                }
                if ($selectedUsers) {
                    foreach ($selectedUsers as $recipient) {
                        Mail::send('email.loginmail', @$replaceddata, function ($msg) use ($recipient, $notification) {
                            $msg->from(env('MAIL_FROM_ADDRESS'));
                            $msg->to($recipient, env('MAIL_FROM_NAME'));
                            $msg->subject($notification->subject);
                        });
                    }
                }
            } else {
                logger('go');
            }

            // $childrenTasksIds = [];
            // $childrenTasksNames = [];
            // foreach ($task->childrenName as $childTask) {
            //     $childrenTasksIds[] = $childTask->id;
            //     $childrenTasksNames[] = $childTask->name;
            // }
            // // logger('=======');
            // // logger('childrenTasksIds');
            // // logger($childrenTasksIds);
            // // logger('=======');
            // // logger('childrenTasksNames');
            // // logger($childrenTasksNames);
            // if (in_array('SendNotification', $childrenTasksNames)) {
            //     logger('=======');
            //     logger('SendNotification');
            // }
            // if (in_array('Stop', $childrenTasksNames)) {
            //     logger('=======');
            //     logger('Stop');
            // }

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

            // dd($tasks);
        } catch (\Exception $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function extractVariablesAndOperators($inputString)
    {
        $tokens = [];
        $operators = ['AND', 'OR', '(', ')'];

        // Build a regular expression pattern for capturing tokens
        $pattern = '/\b(?:' . implode('|', array_map('preg_quote', $operators)) . ')\b|\d+|\(|\)/';

        // Use preg_match_all to capture all matching tokens
        preg_match_all($pattern, $inputString, $matches);

        // Flatten the matches array
        $matches = array_reduce($matches, 'array_merge', []);

        foreach ($matches as $match) {
            $tokens[] = ['type' => is_numeric($match) ? 'variable' : 'operator', 'value' => $match];
        }

        return $tokens;
    }
    public function rebuildString($tokens)
    {
        $result = '';

        foreach ($tokens as $token) {
            if ($token['type'] === 'operator' && ($token['value'] === '(' || $token['value'] === ')')) {
                $result .= $token['value'] . ' ';
            } else {
                $result .= $token['value'] . ' ';
            }
        }

        return trim(preg_replace('/\s+/', ' ', $result));
    }
    public function getVariables($tokens)
    {
        $variables = [];

        foreach ($tokens as $token) {
            if ($token['type'] === 'variable') {
                $variables[] = $token['value'];
            }
        }

        return $variables;
    }
    public function evaluateRulesDestroy($id)
    {
        $evaluateRule = EvaluateRule::find($id);

        if (!$evaluateRule) {
            return redirect()->back()->with('error', 'EvaluateRule not found.');
        }
        $evaluateRule->delete();
        Log::channel('custom')->info('Userid -> ' . auth()->user()->custom_userid . ' , Attachment Deleted by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' , evaluateRule Name -> ' . $evaluateRule->name);

        return redirect()->back()->with('success', 'Successfully Notification Delete.');
    }
    public function TriggerEvaluateContent($id)
    {
        try {
            logger('TriggerEvaluateContent');
            logger($id);
            $evaluateContent = EvaluateContent::where('task_id', $id)->first();

            if ($evaluateContent) {
                $operatorLogic = $evaluateContent->advanced_operator_logic;
                $inputString = $evaluateContent->evaluateRules;
                $fieldDatas = Field::where('application_id', $evaluateContent->application_id)
                    ->where('status', 1)
                    ->get();
                $formDataCheck = FormData::where('application_id', $evaluateContent->application_id)->get();
                $jsonDataArray = [];

                foreach ($formDataCheck as $dataceck) {
                    $jsonData = json_decode($dataceck->data, true);
                    $jsonDataArray[] = $jsonData;
                }

                foreach ($evaluateContent->evaluateRules as $evaluateRule) {
                    $bolos = [];
                    foreach ($jsonDataArray as $value) {
                        $fieldId = $evaluateRule->field_id;
                        if ($evaluateRule->field) {
                            $fieldName = $evaluateRule->field->name;
                            if (array_key_exists($fieldName, $value)) {
                                $fieldValue = $value[$fieldName];
                                switch ($evaluateRule->filter_operator) {
                                    case 'C':
                                        if (strpos($fieldValue, $evaluateRule->filter_value) !== false) {
                                            $bolos[] = true;
                                            logger("Contains comparison: IDs match. Request value: {$fieldValue}, filter value: {$evaluateRule->filter_value}");
                                        } else {
                                            $bolos[] = false;
                                            logger("Contains comparison: IDs do not match. Request value: {$fieldValue}, filter value: {$evaluateRule->filter_value}");
                                        }
                                        break;
                                    case 'DNC':
                                        if (strpos($fieldValue, $evaluateRule->filter_value) === false) {
                                            $bolos[] = true;
                                            logger("Does not contain comparison: IDs match. Request value: {$fieldValue}, filter value: {$evaluateRule->filter_value}");
                                        } else {
                                            $bolos[] = false;
                                            logger("Does not contain comparison: IDs do not match. Request value: {$fieldValue}, filter value: {$evaluateRule->filter_value}");
                                        }
                                        break;
                                    case 'E':
                                        if ($fieldValue == $evaluateRule->filter_value) {
                                            $bolos[] = true;
                                            logger("Equals comparison: IDs match. Request value: {$fieldValue}, filter value: {$evaluateRule->filter_value}");
                                        } else {
                                            $bolos[] = false;
                                            logger("Equals comparison: IDs do not match. Request value: {$fieldValue}, filter value: {$evaluateRule->filter_value}");
                                        }
                                        break;
                                }
                            }
                        }
                    }
                    // Check if any value in $bolos is true
                    if (in_array(true, $bolos)) {
                        return true;
                    }
                }
                // If no true value found in $bolos, return false
                return false;
            } else {
                logger("No evaluate content found for ID: {$id}");
                return false;
            }

            // $evaluateContent = EvaluateContent::where('task_id', $id)->first();
            // $operatorLogic = $evaluateContent->advanced_operator_logic;
            // $inputString = $evaluateContent->evaluateRules;
            // $fieldDatas = Field::where('application_id', $evaluateContent->application_id)
            //     ->where('status', 1)
            //     ->get();
            // $formDataCheck = FormData::where('application_id', $evaluateContent->application_id)->get();
            // $jsonDataArray = [];

            // foreach ($formDataCheck as $dataceck) {
            //     $jsonData = json_decode($dataceck->data, true);
            //     $jsonDataArray[] = $jsonData;
            // }
            // foreach ($evaluateContent->evaluateRules as $evaluateRule) {
            //     $bolos = [];
            //     foreach ($jsonDataArray as $value) {
            //         $fieldId = $evaluateRule->field_id;
            //         $fieldName = $evaluateRule->field->name;
            //         if (array_key_exists($fieldName, $value)) {
            //             $fieldValue = $value[$fieldName];
            //             switch ($evaluateRule->filter_operator) {
            //                 case 'C':
            //                     if (strpos($fieldValue, $evaluateRule->filter_value) !== false) {
            //                         $bolos[] = true;
            //                         logger("Contains comparison: IDs match. Request value: {$fieldValue}, filter value: {$evaluateRule->filter_value}");
            //                     } else {
            //                         $bolos[] = false;
            //                         logger("Contains comparison: IDs do not match. Request value: {$fieldValue}, filter value: {$evaluateRule->filter_value}");
            //                     }
            //                     break;
            //                 case 'DNC':
            //                     if (strpos($fieldValue, $evaluateRule->filter_value) === false) {
            //                         $bolos[] = true;
            //                         logger("Does not contain comparison: IDs match. Request value: {$fieldValue}, filter value: {$evaluateRule->filter_value}");
            //                     } else {
            //                         $bolos[] = false;
            //                         logger("Does not contain comparison: IDs do not match. Request value: {$fieldValue}, filter value: {$evaluateRule->filter_value}");
            //                     }
            //                     break;
            //                 case 'E':
            //                     if ($fieldValue == $evaluateRule->filter_value) {
            //                         $bolos[] = true;

            //                         logger("Equals comparison: IDs match. Request value: {$fieldValue}, filter value: {$evaluateRule->filter_value}");
            //                     } else {
            //                         $bolos[] = false;
            //                         logger("Equals comparison: IDs do not match. Request value: {$fieldValue}, filter value: {$evaluateRule->filter_value}");
            //                     }
            //                     break;
            //             }
            //         }
            //     }
            //     if (in_array(true, $bolos)) {
            //         return true;
            //     }
            //     // logger($bolos);
            //     // if ($inputString) {

            //     //     $extractedTokens = $this->extractVariablesAndOperators($inputString);
            //     //     $variables = $this->getVariables($extractedTokens);
            //     //     $reconstructedString = $this->rebuildString($extractedTokens);
            //     //     foreach ($extractedTokens as &$token) {
            //     //         if ($token['type'] === 'variable') {
            //     //             $variableValue = intval($token['value']);
            //     //             if (isset($bolos[$variableValue - 1])) {
            //     //                 // Set the value of the variable token based on the value in $bolos
            //     //                 $token['value'] = $bolos[$variableValue - 1] ? '1' : '0';
            //     //             }
            //     //         }
            //     //     }

            //     //     $reconstructedString = $this->rebuildString($extractedTokens);
            //     //     $reconstructedString = str_replace('AND', '&&', $reconstructedString);
            //     //     $reconstructedString = str_replace('OR', '||', $reconstructedString);

            //     //     // logger($reconstructedString);
            //     //     logger(eval ("return $reconstructedString;"));
            //     // }
            //     if ($bolos) {
            //         return true;
            //     } else {
            //         return false;

            //     }
            // }
        } catch (\Exception $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function TriggerUpdateContent($id)
    {
        try {
            logger('TriggerUpdateContent');
            logger($id);
            $updateContent = UpdateContent::where('task_id', $id)->first();
            logger($updateContent);
            dd(1234243);
        } catch (\Exception $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function userActionStore(Request $request)
    {
        try {
            UserAction::create([
                'name' => $request->name,
                'task_id' => $request->task_id,
            ]);

            Log::channel('custom')->info('UserAction Created by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname);
            return redirect()->back()->with('success', 'Data saved successfully');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function transitionStore(Request $request)
    {
        try {
            // dd($request->all());
            Transition::create([
                'user_id' => $request->user_id,
                'application_id' => $request->application_id,
                'workflow_id' => $request->workflow_id,
                'task_id' => $request->parent_id,
                'parent_id' => $request->parent_id,
                'child_id' => $request->child_id,
                'condition' => $request->condition,
            ]);

            Log::channel('custom')->info('Transition Created by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname);
            return redirect()->back()->with('success', 'Data saved successfully');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function transitionDestroy($id)
    {
        try {
            $transition = Transition::find($id);
            if (!$transition) {
                return redirect()->back()->with('error', 'Transition not found.');
            }
            $transition->delete();
            Log::channel('custom')->info('UserAction transition Destroy  by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname);
            return redirect()->back()->with('success', 'Data transition delete successfully');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function workflowLogsShow($id)
    {
        try {
            $myLogs = MyLog::where('workflow_id', $id)->get();
            if (!$myLogs) {
                return redirect()->back()->with('error', 'myLogs not found.');
            }
            return view('backend.applications.showLogs', [
                'myLogs' => $myLogs,
            ]);
        } catch (\Exception $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}