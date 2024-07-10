<?php

namespace App\Http\Controllers\backend;

use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Models\backend\EvaluateContent;
use App\Models\backend\TriggerMail;
use Illuminate\Http\Request;
use App\Models\backend\Application;
use App\Models\backend\Field;
use App\Models\backend\Formdata;
use App\Models\User;
use App\Models\backend\Group;
use App\Models\backend\ApplicationIndexing;
use the42coders\Workflows\Workflow;
use Illuminate\Support\Facades\Log;
use the42coders\Workflows\Tasks\Task;
use Illuminate\Support\Facades\Mail;
use App\Traits\WorkflowTraits;
use App\Models\backend\FilterCriteria;
use App\Models\backend\Notification;
use App\Models\backend\Permission;
use App\Models\backend\Role;
use App\Models\backend\UpdateContent;
use Illuminate\Support\Facades\Cache;

class UserApplicationController extends Controller
{
    use WorkflowTraits;

    public function index()
    {
        try {
            //code...
            $loggedinuser = auth()->id();
            // dd($userid);
            $application = Application::where('status', 1)->latest()->get();

            $userapplication = [];
            $userid = [];
            // dd($loggedinuser);

            for ($i = 0; $i < count($application); $i++) {
                # code...
                if ($application[$i]->rolestable()->first() != 'null' && $application[$i]->rolestable()->first() != null) {
                    // dd($application[$i]->rolestable()->first()->group_list);
                    if ($application[$i]->rolestable()->first()->group_list != 'null') {
                        # code...
                        array_push($userid, $this->findusers($application[$i]->rolestable()->first()->group_list));
                    }

                    if ($application[$i]->rolestable()->first()->user_list != 'null') {
                        # code...
                        array_push($userid, json_decode($application[$i]->rolestable()->first()->user_list));
                    }

                    $useridfound = 'false';
                    // dd(in_array(auth()->id(), $userid[2]));
                    for ($j = 0; $j < count($userid); $j++) {
                        if (in_array(auth()->id(), $userid[$j])) {
                            $useridfound = 'true';
                        }
                    }
                    // dd($useridfound);

                    if ($useridfound == 'true') {
                        array_push($userapplication, $application[$i]);
                    }
                }
            }
            $userapplication1 = Application::where(['access' => 'public', 'status' => 1])->get();

            // dd($userapplication, $userapplication1);
            return view('backend.userapplication.index', compact('userapplication', 'userapplication1'));
            // dd($application);
        } catch (\Exception $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $application = Application::find($id);
            $users = User::latest()->get();
            $groups = Group::latest()->get();
            $dbfields = Field::where(['application_id' => $application->id, 'status' => 1])
                ->orderBy('forder', 'ASC')
                ->get();
            $fields = [];
            $userid = [];
            for ($i = 0; $i < count($dbfields); $i++) {
                if ($dbfields[$i]->access == 'private') {
                    # code...
                    if ($dbfields[$i]->groups != 'null') {
                        array_push($userid, $this->findusers($dbfields[$i]->groups));

                        $useridfound = 'false';
                        for ($j = 0; $j < count($userid); $j++) {
                            if (in_array(auth()->id(), $userid[$j])) {
                                $useridfound = 'true';
                            }
                        }

                        if ($useridfound == 'true') {
                            array_push($fields, $dbfields[$i]);
                        }
                    }
                } else {
                    array_push($fields, $dbfields[$i]);
                }
            }

            return view('backend.userapplication.edit', compact('groups', 'id', 'users', 'application', 'fields'));
            // dd($application);
        } catch (\Exception $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     $data = $request->all();

    //     unset($data['_token']);
    //     unset($data['_method']);
    //     unset($data['userid']);
    //     unset($data['formdataid']);
    //     // dd($data);
    //     foreach (request()->allFiles() as $key => $value) {
    //         if ($value->getSize() > 2e6) {
    //             # code...
    //             throw new Exception('File Size is more then 2 mb');
    //         } else {
    //             # code...
    //             unset($data[$key]);
    //             $filename = rand() . $value->getClientOriginalName();
    //             $value->move(public_path('files'), $filename);
    //             $data[$key] = $filename;
    //         }
    //     }
    //     // dd($data);
    //     $application = Application::find($id);

    //     if (isset($request->formdataid)) {
    //         # code...
    //         $data1['data'] = json_encode($data);
    //         $data1['userid'] = $request->userid;
    //         $data1['application_id'] = $id;
    //         // dd($data1);
    //         $formdata = Formdata::find($request->formdataid);
    //         $currentarray = $formdata->data;
    //         $changearray = $data1['data'];
    //         $formdata->update($data1);
    //         Log::channel('user')->info('Userid -> ' . auth()->user()->custom_userid . ' , Application Edited by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' Application Name -> ' . $application->name . ' Current Data -> ' . $currentarray . ' Change Data -> ' . $changearray);

    //         return redirect()
    //             ->back()
    //             ->with('success', 'Form Updated.');
    //     } else {
    //         # code...
    //         $data1['data'] = json_encode($data);
    //         $data1['userid'] = $request->userid;
    //         $data1['application_id'] = $id;
    //         // dd($data);
    //         //workflow functionality
    //         // $workflow = Workflow::where('application_id', $id)->first();
    //         // $tasks = Task::where('workflow_id', $workflow->id)
    //         //     ->latest()
    //         //     ->get();
    //         // dd($workflow, $tasks);
    //         // $this->workflow($tasks);
    //         // for ($i = 0; $i < count($tasks); $i++) {
    //         //     # code...
    //         //     if ($tasks[$i]->name == 'SendNotification') {
    //         //         # code...
    //         //         $sendmail = false;
    //         //         $wdata = json_decode($tasks[$i]->data_fields);
    //         //         // dd($data);
    //         //         $subject = $wdata->name;
    //         //         $notification = $wdata->notification;

    //         //     }

    //         //     $parenttask = Task::where('id', $tasks[$i]->parentable_id)->first();
    //         //     // // dd($parenttask);
    //         //     // if (isset($parenttask->name) && $parenttask->name == 'EvaluateContent') {
    //         //     //     $wdata1 = json_decode($parenttask->data_fields);
    //         //     //     // dd($wdata1);
    //         //     //     for ($j = 0; $j < count($wdata1->fieldname); $j++) {
    //         //     //         # code...
    //         //     //         if (array_key_exists($wdata1->fieldname[$j], $data)) {
    //         //     //             # code...
    //         //     //             // dd($wdata1->fieldname[$j], $data);
    //         //     //             if ($wdata1->operators[$j] == 'equal') {
    //         //     //                 # code...
    //         //     //                 if ($data[$wdata1->fieldname[$j]] == $wdata1->values[$j]) {

    //         //     //                     # code...
    //         //     //                     // dd($notification);
    //         //     //                     $mailsend = Mail::send('email.useraction', ['data' => $notification], function ($message) use($notification, $subject) {
    //         //     //                         $message->sender('jakpower@omegawebdemo.com.au');
    //         //     //                         $message->subject($subject);
    //         //     //                         $message->to(auth()->user()->email);
    //         //     //                     });
    //         //     //                 }
    //         //     //             }
    //         //     //         }
    //         //     //     }
    //         //     // }
    //         // }
    //         Log::channel('user')->info('Application Created by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' Application Name -> ' . $application->name . ' Current Data -> ' . $data1['data']);
    //         // dd('Demo purpose only ask if condition match form create or not.');
    //         Formdata::create($data1);
    //         return redirect()
    //             ->route('userapplication.list', $id)
    //             ->with('success', 'Form Saved.');
    //     }
    //     try {
    //     } catch (\Exception $th) {
    //         //throw $th;
    //         //throw $th;
    //         return redirect()
    //             ->back()
    //             ->with('error', $th->getMessage());
    //     }
    // }
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
    public function update(Request $request, $id)
    {
        try {
            $data = $request->all();
            unset($data['_token']);
            unset($data['_method']);
            unset($data['userid']);
            unset($data['formdataid']);

            foreach (request()->allFiles() as $key => $value) {
                if ($value->getSize() > 2e6) {
                    # code...
                    throw new \Exception('File Size is more then 2 mb');
                } else {
                    # code...
                    unset($data[$key]);
                    $filename = rand() . $value->getClientOriginalName();
                    $value->move(public_path('files'), $filename);
                    $data[$key] = $filename;
                }
            }

            $application = Application::find($id);
            if (!$application) {
                logger('Application not found with ID: ' . $id);
                return;
            }

            if ($application->workFlow && $application->workFlow->id) {
                logger('Workflow ID found: ' . $application->workFlow->id);
                $requestData = request()->all();
                $requestData['application_id'] = $id;
                // logger($requestData);
                $this->triggerButtonShow($requestData, $application->workFlow->id);

                $logData = Cache::get('data');
                logger($logData);
                Cache::forget('data');
                if (!in_array('UpdateContent', $logData)) {
                    logger('No Workflow ID found for Application ID: ' . $id);
                    $fieldDatas = Field::where('application_id', $application->id)
                        ->where('status', 1)
                        ->get();
                    $notifications = Notification::where('active', 'Y')->where('recurring', 'instantly')->where('application_id', $id)->get();
                    $validationRules = [];
                    foreach ($fieldDatas as $field) {
                        $rules = [];
                        if ($field->type == 'attachment' || $field->type == 'images') {
                            // File validation rules
                            $rules[] = ''; // Adjust as per your requirements
                        } else {
                            if ($field->requiredfield == 1) {
                                $rules[] = 'required';
                            }
                            if ($field->requireuniquevalue == 1) {
                                $formDataCheck = FormData::where('application_id', $application->id)->get();
                                $jsonDataArray = [];

                                foreach ($formDataCheck as $dataceck) {
                                    $jsonData = json_decode($dataceck->data, true);
                                    $jsonDataArray[] = $jsonData;
                                }
                                foreach ($jsonDataArray as $jsonData) {
                                    if ($this->isDataMatch($request->all(), $jsonData)) {
                                        return redirect()->back()->with('error', 'Duplicate data found!');
                                    }
                                }
                            }
                        }

                        $validationRules[$field->name] = $rules;
                    }

                    $request->validate($validationRules);

                    foreach ($notifications as $notification) {
                        $inputString = $notification->advanced_operator_logic;
                        $allFilterCriterias = $notification->filterCriterias;
                        $bolos = [];
                        foreach ($fieldDatas as $value) {
                            foreach ($notification->filterCriterias as $filterCriteria) {
                                if ($filterCriteria->field_id == $value->id) {
                                    switch ($filterCriteria->filter_operator) {
                                        case 'C':
                                            if (strpos($request[$value->name], $filterCriteria->filter_value) !== false) {
                                                $bolos[] = true;
                                                logger("Contains comparison: IDs match. Request value: {$request[$value->name]}, filter value: {$filterCriteria->filter_value}");
                                            } else {
                                                $bolos[] = false;
                                                logger("Contains comparison: IDs do not match. Request value: {$request[$value->name]}, filter value: {$filterCriteria->filter_value}");
                                            }
                                            break;
                                        case 'DNC':
                                            if (strpos($request[$value->name], $filterCriteria->filter_value) === false) {
                                                $bolos[] = true;
                                                logger("Does not contain comparison: IDs match. Request value: {$request[$value->name]}, filter value: {$filterCriteria->filter_value}");
                                            } else {
                                                $bolos[] = false;
                                                logger("Does not contain comparison: IDs do not match. Request value: {$request[$value->name]}, filter value: {$filterCriteria->filter_value}");
                                            }
                                            break;
                                        case 'E':
                                            if ($request[$value->name] == $filterCriteria->filter_value) {
                                                $bolos[] = true;

                                                logger("Equals comparison: IDs match. Request value: {$request[$value->name]}, filter value: {$filterCriteria->filter_value}");
                                            } else {
                                                $bolos[] = false;
                                                logger("Equals comparison: IDs do not match. Request value: {$request[$value->name]}, filter value: {$filterCriteria->filter_value}");
                                            }
                                            break;
                                        case 'CH': // Changed
                                            // Perform action for 'Changed' case
                                            break;
                                        case 'CT': // Changed To
                                            // Perform action for 'Changed To' case
                                            break;
                                        case 'CF': // Changed From
                                            // Perform action for 'Changed From' case
                                            break;
                                        // Handle other comparison cases
                                    }
                                }
                            }
                        }

                        $extractedTokens = $this->extractVariablesAndOperators($inputString);
                        $variables = $this->getVariables($extractedTokens);
                        $reconstructedString = $this->rebuildString($extractedTokens);
                        foreach ($extractedTokens as &$token) {
                            if ($token['type'] === 'variable') {
                                $variableValue = intval($token['value']);
                                if (isset($bolos[$variableValue - 1])) {
                                    $token['value'] = $bolos[$variableValue - 1] ? '1' : '0';
                                }
                            }
                        }

                        $reconstructedString = $this->rebuildString($extractedTokens);
                        $reconstructedString = str_replace('AND', '&&', $reconstructedString);
                        $reconstructedString = str_replace('OR', '||', $reconstructedString);

                        // logger($reconstructedString);
                        logger(eval("return $reconstructedString;"));
                        if (eval("return $reconstructedString;")) {
                            logger('Email sent!');
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
                                // Decode the JSON string to an array
                                $groupUserIdsArray = json_decode($groupUserIds, true);

                                // Check if $groupUserIdsArray is an array
                                if (!is_array($groupUserIdsArray)) {
                                    $this->error('Invalid data for groupUserIds: ' . $groupUserIds);
                                    continue; // Skip to the next iteration if data is invalid
                                }

                                foreach ($groupUserIdsArray as $userId) {
                                    // Check if $userId is a valid integer
                                    if (!is_numeric($userId) || intval($userId) <= 0) {
                                        $this->error("Invalid user ID: $userId");
                                        continue; // Skip to the next iteration if user ID is invalid
                                    }

                                    // Find the user by ID
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

                                // Replace placeholders with values
                                $replacedTemplate = $template;

                                foreach ($data as $key => $value) {
                                    $placeholder = "[field:$key]";
                                    $replacedTemplate = str_replace($placeholder, $value, $replacedTemplate);
                                }

                                $replacedTemplates[] = $replacedTemplate;
                                logger($replacedTemplate);
                            });

                            // dd($replacedTemplates);
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
                            logger('---false---');
                        }
                    }

                    if (isset($request->formdataid)) {
                        $data1['data'] = json_encode($data);
                        $data1['userid'] = $request->userid;
                        $data1['application_id'] = $id;
                        $formdata = Formdata::find($request->formdataid);
                        $currentarray = $formdata->data;
                        $changearray = $data1['data'];
                        $formdata->update($data1);
                        Log::channel('user')->info('Userid -> ' . auth()->user()->custom_userid . ' , Application Edited by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' Application Name -> ' . $application->name . ' Current Data -> ' . $currentarray . ' Change Data -> ' . $changearray);

                        return redirect()->back()->with('success', 'Form Updated.');
                    } else {
                        # code...
                        $data1['data'] = json_encode($data);
                        $data1['userid'] = $request->userid;
                        $data1['application_id'] = $id;
                        Log::channel('user')->info('Application Created by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' Application Name -> ' . $application->name . ' Current Data -> ' . $data1['data']);
                        Formdata::create($data1);
                        return redirect()->route('userapplication.list', $id)->with('success', 'Form Saved.');
                    }
                }
                // dd(12);
                return redirect()->back();
            } else {
                logger('No Workflow ID found for Application ID: ' . $id);
                $fieldDatas = Field::where('application_id', $application->id)
                    ->where('status', 1)
                    ->get();
                $notifications = Notification::where('active', 'Y')->where('recurring', 'instantly')->where('application_id', $id)->get();
                $validationRules = [];

                foreach ($fieldDatas as $field) {
                    $rules = [];

                    // Handle file type separately
                    if ($field->type == 'attachment' || $field->type == 'images') {
                        // File validation rules
                        $rules[] = ''; // Adjust as per your requirements
                    } else {
                        if ($field->requiredfield == 1) {
                            $rules[] = 'required';
                        }
                        if ($field->requireuniquevalue == 1) {
                            $formDataCheck = FormData::where('application_id', $application->id)->get();
                            $jsonDataArray = [];

                            foreach ($formDataCheck as $dataceck) {
                                $jsonData = json_decode($dataceck->data, true);
                                $jsonDataArray[] = $jsonData;
                            }
                            foreach ($jsonDataArray as $jsonData) {
                                if ($this->isDataMatch($request->all(), $jsonData)) {
                                    return redirect()->back()->with('error', 'Duplicate data found!');
                                }
                            }
                        }
                    }
                    $sanitizedFieldName = str_replace(' ', '_', $field->name);
                    $validationRules[$sanitizedFieldName] = $rules;
                }

                $request->validate($validationRules);

                foreach ($notifications as $notification) {
                    $inputString = $notification->advanced_operator_logic;
                    $allFilterCriterias = $notification->filterCriterias;
                    $bolos = [];
                    foreach ($fieldDatas as $value) {
                        foreach ($notification->filterCriterias as $filterCriteria) {
                            if ($filterCriteria->field_id == $value->id) {
                                switch ($filterCriteria->filter_operator) {
                                    case 'C':
                                        if (strpos($request[$value->name], $filterCriteria->filter_value) !== false) {
                                            $bolos[] = true;
                                            logger("Contains comparison: IDs match. Request value: {$request[$value->name]}, filter value: {$filterCriteria->filter_value}");
                                        } else {
                                            $bolos[] = false;
                                            logger("Contains comparison: IDs do not match. Request value: {$request[$value->name]}, filter value: {$filterCriteria->filter_value}");
                                        }
                                        break;
                                    case 'DNC':
                                        if (strpos($request[$value->name], $filterCriteria->filter_value) === false) {
                                            $bolos[] = true;
                                            logger("Does not contain comparison: IDs match. Request value: {$request[$value->name]}, filter value: {$filterCriteria->filter_value}");
                                        } else {
                                            $bolos[] = false;
                                            logger("Does not contain comparison: IDs do not match. Request value: {$request[$value->name]}, filter value: {$filterCriteria->filter_value}");
                                        }
                                        break;
                                    case 'E':
                                        if ($request[$value->name] == $filterCriteria->filter_value) {
                                            $bolos[] = true;

                                            logger("Equals comparison: IDs match. Request value: {$request[$value->name]}, filter value: {$filterCriteria->filter_value}");
                                        } else {
                                            $bolos[] = false;
                                            logger("Equals comparison: IDs do not match. Request value: {$request[$value->name]}, filter value: {$filterCriteria->filter_value}");
                                        }
                                        break;
                                    case 'CH': // Changed
                                        // Perform action for 'Changed' case
                                        break;
                                    case 'CT': // Changed To
                                        // Perform action for 'Changed To' case
                                        break;
                                    case 'CF': // Changed From
                                        // Perform action for 'Changed From' case
                                        break;
                                    // Handle other comparison cases
                                }
                            }
                        }
                    }

                    $extractedTokens = $this->extractVariablesAndOperators($inputString);
                    $variables = $this->getVariables($extractedTokens);
                    $reconstructedString = $this->rebuildString($extractedTokens);
                    foreach ($extractedTokens as &$token) {
                        if ($token['type'] === 'variable') {
                            $variableValue = intval($token['value']);
                            if (isset($bolos[$variableValue - 1])) {
                                $token['value'] = $bolos[$variableValue - 1] ? '1' : '0';
                            }
                        }
                    }

                    $reconstructedString = $this->rebuildString($extractedTokens);
                    $reconstructedString = str_replace('AND', '&&', $reconstructedString);
                    $reconstructedString = str_replace('OR', '||', $reconstructedString);

                    // logger($reconstructedString);
                    logger(eval("return $reconstructedString;"));
                    if (eval("return $reconstructedString;")) {
                        logger('Email sent!');
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
                            // Decode the JSON string to an array
                            $groupUserIdsArray = json_decode($groupUserIds, true);

                            // Check if $groupUserIdsArray is an array
                            if (!is_array($groupUserIdsArray)) {
                                $this->error('Invalid data for groupUserIds: ' . $groupUserIds);
                                continue; // Skip to the next iteration if data is invalid
                            }

                            foreach ($groupUserIdsArray as $userId) {
                                // Check if $userId is a valid integer
                                if (!is_numeric($userId) || intval($userId) <= 0) {
                                    $this->error("Invalid user ID: $userId");
                                    continue; // Skip to the next iteration if user ID is invalid
                                }

                                // Find the user by ID
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

                            // Replace placeholders with values
                            $replacedTemplate = $template;

                            foreach ($data as $key => $value) {
                                $placeholder = "[field:$key]";
                                $replacedTemplate = str_replace($placeholder, $value, $replacedTemplate);
                            }

                            $replacedTemplates[] = $replacedTemplate;
                            logger($replacedTemplate);
                        });

                        // dd($replacedTemplates);
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
                        logger('---false---');
                    }
                }

                if (isset($request->formdataid)) {
                    $data1['data'] = json_encode($data);
                    $data1['userid'] = $request->userid;
                    $data1['application_id'] = $id;
                    $formdata = Formdata::find($request->formdataid);
                    $currentarray = $formdata->data;
                    $changearray = $data1['data'];
                    $formdata->update($data1);
                    Log::channel('user')->info('Userid -> ' . auth()->user()->custom_userid . ' , Application Edited by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' Application Name -> ' . $application->name . ' Current Data -> ' . $currentarray . ' Change Data -> ' . $changearray);

                    return redirect()->back()->with('success', 'Form Updated.');
                } else {
                    # code...
                    $data1['data'] = json_encode($data);
                    $data1['userid'] = $request->userid;
                    $data1['application_id'] = $id;
                    Log::channel('user')->info('Application Created by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' Application Name -> ' . $application->name . ' Current Data -> ' . $data1['data']);
                    Formdata::create($data1);
                    return redirect()->route('userapplication.list', $id)->with('success', 'Form Saved.');
                }
            }
        } catch (\Exception $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function updateEdit(Request $request, $id)
    {
        try {
            $data = $request->all();
            unset($data['_token']);
            unset($data['_method']);
            unset($data['userid']);
            unset($data['formdataid']);

            foreach (request()->allFiles() as $key => $value) {
                if ($value->getSize() > 2e6) {
                    # code...
                    throw new \Exception('File Size is more then 2 mb');
                } else {
                    # code...
                    unset($data[$key]);
                    $filename = rand() . $value->getClientOriginalName();
                    $value->move(public_path('files'), $filename);
                    $data[$key] = $filename;
                }
            }

            $application = Application::find($id);
            if (!$application) {
                logger('Application not found with ID: ' . $id);
                return;
            }

            if ($application->workFlow && $application->workFlow->id) {
                logger('Workflow ID found: ' . $application->workFlow->id);
                $requestData = request()->all();
                $requestData['application_id'] = $id;
                // logger($requestData);
                $this->triggerButtonShow($requestData, $application->workFlow->id);

                $logData = Cache::get('data');
                logger($logData);
                Cache::forget('data');
                if (!in_array('UpdateContent', $logData)) {
                    logger('No Workflow ID found for Application ID: ' . $id);
                    $fieldDatas = Field::where('application_id', $application->id)
                        ->where('status', 1)
                        ->get();
                    $notifications = Notification::where('active', 'Y')->where('recurring', 'instantly')->where('application_id', $id)->get();


                    foreach ($notifications as $notification) {
                        $inputString = $notification->advanced_operator_logic;
                        $allFilterCriterias = $notification->filterCriterias;
                        $bolos = [];
                        foreach ($fieldDatas as $value) {
                            foreach ($notification->filterCriterias as $filterCriteria) {
                                if ($filterCriteria->field_id == $value->id) {
                                    switch ($filterCriteria->filter_operator) {
                                        case 'C':
                                            if (strpos($request[$value->name], $filterCriteria->filter_value) !== false) {
                                                $bolos[] = true;
                                                logger("Contains comparison: IDs match. Request value: {$request[$value->name]}, filter value: {$filterCriteria->filter_value}");
                                            } else {
                                                $bolos[] = false;
                                                logger("Contains comparison: IDs do not match. Request value: {$request[$value->name]}, filter value: {$filterCriteria->filter_value}");
                                            }
                                            break;
                                        case 'DNC':
                                            if (strpos($request[$value->name], $filterCriteria->filter_value) === false) {
                                                $bolos[] = true;
                                                logger("Does not contain comparison: IDs match. Request value: {$request[$value->name]}, filter value: {$filterCriteria->filter_value}");
                                            } else {
                                                $bolos[] = false;
                                                logger("Does not contain comparison: IDs do not match. Request value: {$request[$value->name]}, filter value: {$filterCriteria->filter_value}");
                                            }
                                            break;
                                        case 'E':
                                            if ($request[$value->name] == $filterCriteria->filter_value) {
                                                $bolos[] = true;

                                                logger("Equals comparison: IDs match. Request value: {$request[$value->name]}, filter value: {$filterCriteria->filter_value}");
                                            } else {
                                                $bolos[] = false;
                                                logger("Equals comparison: IDs do not match. Request value: {$request[$value->name]}, filter value: {$filterCriteria->filter_value}");
                                            }
                                            break;
                                        case 'CH': // Changed
                                            // Perform action for 'Changed' case
                                            break;
                                        case 'CT': // Changed To
                                            // Perform action for 'Changed To' case
                                            break;
                                        case 'CF': // Changed From
                                            // Perform action for 'Changed From' case
                                            break;
                                        // Handle other comparison cases
                                    }
                                }
                            }
                        }

                        $extractedTokens = $this->extractVariablesAndOperators($inputString);
                        $variables = $this->getVariables($extractedTokens);
                        $reconstructedString = $this->rebuildString($extractedTokens);
                        foreach ($extractedTokens as &$token) {
                            if ($token['type'] === 'variable') {
                                $variableValue = intval($token['value']);
                                if (isset($bolos[$variableValue - 1])) {
                                    $token['value'] = $bolos[$variableValue - 1] ? '1' : '0';
                                }
                            }
                        }

                        $reconstructedString = $this->rebuildString($extractedTokens);
                        $reconstructedString = str_replace('AND', '&&', $reconstructedString);
                        $reconstructedString = str_replace('OR', '||', $reconstructedString);

                        // logger($reconstructedString);
                        logger(eval("return $reconstructedString;"));
                        if (eval("return $reconstructedString;")) {
                            logger('Email sent!');
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
                                // Decode the JSON string to an array
                                $groupUserIdsArray = json_decode($groupUserIds, true);

                                // Check if $groupUserIdsArray is an array
                                if (!is_array($groupUserIdsArray)) {
                                    $this->error('Invalid data for groupUserIds: ' . $groupUserIds);
                                    continue; // Skip to the next iteration if data is invalid
                                }

                                foreach ($groupUserIdsArray as $userId) {
                                    // Check if $userId is a valid integer
                                    if (!is_numeric($userId) || intval($userId) <= 0) {
                                        $this->error("Invalid user ID: $userId");
                                        continue; // Skip to the next iteration if user ID is invalid
                                    }

                                    // Find the user by ID
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

                                // Replace placeholders with values
                                $replacedTemplate = $template;

                                foreach ($data as $key => $value) {
                                    $placeholder = "[field:$key]";
                                    $replacedTemplate = str_replace($placeholder, $value, $replacedTemplate);
                                }

                                $replacedTemplates[] = $replacedTemplate;
                                logger($replacedTemplate);
                            });

                            // dd($replacedTemplates);
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
                            logger('---false---');
                        }
                    }

                    if (isset($request->formdataid)) {
                        $data1['data'] = json_encode($data);
                        $data1['userid'] = $request->userid;
                        $data1['application_id'] = $id;
                        $formdata = Formdata::find($request->formdataid);
                        $currentarray = $formdata->data;
                        $changearray = $data1['data'];
                        $formdata->update($data1);
                        Log::channel('user')->info('Userid -> ' . auth()->user()->custom_userid . ' , Application Edited by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' Application Name -> ' . $application->name . ' Current Data -> ' . $currentarray . ' Change Data -> ' . $changearray);

                        return redirect()->back()->with('success', 'Form Updated.');
                    } else {
                        # code...
                        $data1['data'] = json_encode($data);
                        $data1['userid'] = $request->userid;
                        $data1['application_id'] = $id;
                        Log::channel('user')->info('Application Created by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' Application Name -> ' . $application->name . ' Current Data -> ' . $data1['data']);
                        Formdata::create($data1);
                        return redirect()->route('userapplication.list', $id)->with('success', 'Form Saved.');
                    }
                }
                // dd(12);
                return redirect()->back();
            } else {
                logger('No Workflow ID found for Application ID: ' . $id);
                $fieldDatas = Field::where('application_id', $application->id)
                    ->where('status', 1)
                    ->get();
                $notifications = Notification::where('active', 'Y')->where('recurring', 'instantly')->where('application_id', $id)->get();
                $validationRules = [];


                foreach ($notifications as $notification) {
                    $inputString = $notification->advanced_operator_logic;
                    $allFilterCriterias = $notification->filterCriterias;
                    $bolos = [];
                    foreach ($fieldDatas as $value) {
                        foreach ($notification->filterCriterias as $filterCriteria) {
                            if ($filterCriteria->field_id == $value->id) {
                                switch ($filterCriteria->filter_operator) {
                                    case 'C':
                                        if (strpos($request[$value->name], $filterCriteria->filter_value) !== false) {
                                            $bolos[] = true;
                                            logger("Contains comparison: IDs match. Request value: {$request[$value->name]}, filter value: {$filterCriteria->filter_value}");
                                        } else {
                                            $bolos[] = false;
                                            logger("Contains comparison: IDs do not match. Request value: {$request[$value->name]}, filter value: {$filterCriteria->filter_value}");
                                        }
                                        break;
                                    case 'DNC':
                                        if (strpos($request[$value->name], $filterCriteria->filter_value) === false) {
                                            $bolos[] = true;
                                            logger("Does not contain comparison: IDs match. Request value: {$request[$value->name]}, filter value: {$filterCriteria->filter_value}");
                                        } else {
                                            $bolos[] = false;
                                            logger("Does not contain comparison: IDs do not match. Request value: {$request[$value->name]}, filter value: {$filterCriteria->filter_value}");
                                        }
                                        break;
                                    case 'E':
                                        if ($request[$value->name] == $filterCriteria->filter_value) {
                                            $bolos[] = true;

                                            logger("Equals comparison: IDs match. Request value: {$request[$value->name]}, filter value: {$filterCriteria->filter_value}");
                                        } else {
                                            $bolos[] = false;
                                            logger("Equals comparison: IDs do not match. Request value: {$request[$value->name]}, filter value: {$filterCriteria->filter_value}");
                                        }
                                        break;
                                    case 'CH': // Changed
                                        // Perform action for 'Changed' case
                                        break;
                                    case 'CT': // Changed To
                                        // Perform action for 'Changed To' case
                                        break;
                                    case 'CF': // Changed From
                                        // Perform action for 'Changed From' case
                                        break;
                                    // Handle other comparison cases
                                }
                            }
                        }
                    }

                    $extractedTokens = $this->extractVariablesAndOperators($inputString);
                    $variables = $this->getVariables($extractedTokens);
                    $reconstructedString = $this->rebuildString($extractedTokens);
                    foreach ($extractedTokens as &$token) {
                        if ($token['type'] === 'variable') {
                            $variableValue = intval($token['value']);
                            if (isset($bolos[$variableValue - 1])) {
                                $token['value'] = $bolos[$variableValue - 1] ? '1' : '0';
                            }
                        }
                    }

                    $reconstructedString = $this->rebuildString($extractedTokens);
                    $reconstructedString = str_replace('AND', '&&', $reconstructedString);
                    $reconstructedString = str_replace('OR', '||', $reconstructedString);

                    // logger($reconstructedString);
                    logger(eval("return $reconstructedString;"));
                    if (eval("return $reconstructedString;")) {
                        logger('Email sent!');
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
                            // Decode the JSON string to an array
                            $groupUserIdsArray = json_decode($groupUserIds, true);

                            // Check if $groupUserIdsArray is an array
                            if (!is_array($groupUserIdsArray)) {
                                $this->error('Invalid data for groupUserIds: ' . $groupUserIds);
                                continue; // Skip to the next iteration if data is invalid
                            }

                            foreach ($groupUserIdsArray as $userId) {
                                // Check if $userId is a valid integer
                                if (!is_numeric($userId) || intval($userId) <= 0) {
                                    $this->error("Invalid user ID: $userId");
                                    continue; // Skip to the next iteration if user ID is invalid
                                }

                                // Find the user by ID
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

                            // Replace placeholders with values
                            $replacedTemplate = $template;

                            foreach ($data as $key => $value) {
                                $placeholder = "[field:$key]";
                                $replacedTemplate = str_replace($placeholder, $value, $replacedTemplate);
                            }

                            $replacedTemplates[] = $replacedTemplate;
                            logger($replacedTemplate);
                        });

                        // dd($replacedTemplates);
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
                        logger('---false---');
                    }
                }

                if (isset($request->formdataid)) {
                    $data1['data'] = json_encode($data);
                    $data1['userid'] = $request->userid;
                    $data1['application_id'] = $id;
                    $formdata = Formdata::find($request->formdataid);
                    $currentarray = $formdata->data;
                    $changearray = $data1['data'];
                    $formdata->update($data1);
                    Log::channel('user')->info('Userid -> ' . auth()->user()->custom_userid . ' , Application Edited by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' Application Name -> ' . $application->name . ' Current Data -> ' . $currentarray . ' Change Data -> ' . $changearray);

                    return redirect()->back()->with('success', 'Form Updated.');
                } else {
                    # code...
                    $data1['data'] = json_encode($data);
                    $data1['userid'] = $request->userid;
                    $data1['application_id'] = $id;
                    Log::channel('user')->info('Application Created by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' Application Name -> ' . $application->name . ' Current Data -> ' . $data1['data']);
                    Formdata::create($data1);
                    return redirect()->route('userapplication.list', $id)->with('success', 'Form Saved.');
                }
            }
        } catch (\Exception $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function triggerButtonShow($requestData, $triggerId)
    {
        $task = Task::where('workflow_id', $triggerId)->first();
        $parentTask = $task->parentableName ?? $task;
        $parentTaskName = $parentTask->name ?? '';

        $existingTasks = Cache::get('data', []);
        $existingTasks[] = $parentTaskName;
        Cache::put('data', $existingTasks);

        $childrenTasksIds = [];
        if ($parentTask && !is_null($parentTask->childrenName)) {
            foreach ($parentTask->childrenName as $childTask) {
                $childrenTasksIds[] = $childTask->id;
            }
        }

        switch ($parentTaskName) {
            case 'EvaluateContent':
                logger('=======');
                logger('EvaluateContent');
                $getValue = $this->TriggerEvaluateContent($requestData, $parentTask->id);
                $this->triggerButtonChildren($requestData, $getValue ? $childrenTasksIds[0] : $childrenTasksIds[1]);
                break;

            case 'SendNotification':
                logger('=======');
                logger('SendNotification');
                $this->TriggerSendMail($parentTask->id);
                $this->triggerButtonChildren($requestData, $parentTask->id);
                break;

            case 'UpdateContent':
                logger('=======');
                logger('UpdateContent');
                $this->TriggerUpdateContent($requestData, $parentTask->id);
                $this->triggerButtonChildren($requestData, $parentTask->id);
                break;

            case 'UserAction':
                logger('=======');
                logger('UserAction');
                $this->triggerButtonChildren($requestData, $parentTask->id);
                break;

            default:
                logger('No Workflow ID found for Application ID: ' . $requestData);
                return redirect()->back()->with('error', 'not found');
        }

        return redirect()->back()->with('success', 'Button Triggered a Workflow');
    }

    public function triggerButtonChildren($requestData, $triggerId)
    {
        logger('triggerButtonChildren');

        $tasks = Task::find($triggerId);
        logger($tasks);
        $existingTasks = Cache::get('data', []);
        $existingTasks[] = $tasks->name;
        Cache::put('data', $existingTasks);

        $childrenTasksIds = [];
        $childrenTasksNames = [];
        foreach ($tasks->childrenName as $childTask) {
            $childrenTasksIds[] = $childTask->id;
            $childrenTasksNames[] = $childTask->name;
        }

        if ($tasks->name == 'SendNotification') {
            logger('=======');
            logger('SendNotification');
            if ($childrenTasksIds) {
                $this->TriggerSendMail($childrenTasksIds[0]);
                $this->triggerButtonChildren($requestData, $childrenTasksIds[0]);
            } else {
                $this->TriggerSendMail($tasks->id);
            }
        } elseif ($tasks->name == 'EvaluateContent') {
            logger('=======');
            logger('EvaluateContent');
            $this->triggerButtonChildren($requestData, $childrenTasksIds[0]);
        } elseif ($tasks->name == 'UpdateContent') {
            logger('=======');
            logger('UpdateContent');
            // logger($childrenTasksIds);
            $this->TriggerUpdateContent($requestData, $tasks->id);
            $this->triggerButtonChildren($requestData, $childrenTasksIds[0]);
        } elseif ($tasks->name == 'UserAction') {
            logger('=======');
            logger('UserAction');
        } else {
            logger('No Workflow ID found for Application ID: ');
        }
        return redirect()->back()->with('success', 'Button Triggered a Workflow');
    }
    public function TriggerSendMail($id)
    {
        try {
            logger('TriggerSendMail');
            // logger($id);

            $tasksGet = Task::find($id);
            // logger($tasksGet);

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
    public function TriggerEvaluateContent($requestData, $id)
    {
        try {
            $evaluateContent = EvaluateContent::where('task_id', $id)->first();
            if ($evaluateContent) {
                $operatorLogic = $evaluateContent->advanced_operator_logic;
                $bolos = [];
                foreach ($evaluateContent->evaluateRules as $evaluateRule) {
                    $fieldName = $evaluateRule->field->name;
                    $fieldValue = $requestData[$fieldName] ?? null;
                    switch ($evaluateRule->filter_operator) {
                        case 'C':
                            if ($fieldValue !== null && strpos($fieldValue, $evaluateRule->filter_value) !== false) {
                                $bolos[] = true;
                                logger("Contains comparison: IDs match. Request value: {$fieldValue}, filter value: {$evaluateRule->filter_value}");
                            } else {
                                $bolos[] = false;
                                logger("Contains comparison: IDs do not match. Request value: {$fieldValue}, filter value: {$evaluateRule->filter_value}");
                            }
                            break;
                        case 'DNC':
                            if ($fieldValue !== null && strpos($fieldValue, $evaluateRule->filter_value) === false) {
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
                        case 'DNE':
                            if ($fieldValue !== $evaluateRule->filter_value) {
                                $bolos[] = true;
                                logger("Equals comparison: IDs match. Request value: {$fieldValue}, filter value: {$evaluateRule->filter_value}");
                            } else {
                                $bolos[] = false;
                                logger("Equals comparison: IDs do not match. Request value: {$fieldValue}, filter value: {$evaluateRule->filter_value}");
                            }
                            break;
                    }
                }
                if ($operatorLogic) {
                    $extractedTokens = $this->extractVariablesAndOperators($operatorLogic);
                    $variables = $this->getVariables($extractedTokens);
                    $reconstructedString = $this->rebuildString($extractedTokens);
                    foreach ($extractedTokens as &$token) {
                        if ($token['type'] === 'variable') {
                            $variableValue = intval($token['value']);
                            if (isset($bolos[$variableValue - 1])) {
                                $token['value'] = $bolos[$variableValue - 1] ? '1' : '0';
                            }
                        }
                    }
                    $reconstructedString = $this->rebuildString($extractedTokens);
                    $reconstructedString = str_replace('AND', '&&', $reconstructedString);
                    $reconstructedString = str_replace('OR', '||', $reconstructedString);
                    logger("Evaluated advanced operator logic: $reconstructedString");
                    logger(eval("return $reconstructedString;"));
                    if (eval("return $reconstructedString;")) {
                        return true;
                    }
                } else {
                    $arrayAsString = implode(
                        ' && ',
                        array_map(function ($value) {
                            return $value ? 'true' : 'false';
                        }, $bolos),
                    );
                    if (eval("return $arrayAsString;")) {
                        return true;
                    }
                }
                return false;
            } else {
                logger("No evaluate content found for ID: {$id}");
                return false;
            }
        } catch (\Exception $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function TriggerUpdateContent($requestData, $id)
    {
        try {
            logger('UserID missing in the request data');

            $filteredData = array_diff_key($requestData, array_flip(['_method', '_token']));
            // logger($filteredData);
            // logger($requestData);
            $updateContent = UpdateContent::where('task_id', $id)->first();
            if (!$updateContent) {
                logger('No updateContent found for task_id: ' . $id);
                return back()->with('error', 'No content found for the provided task ID.');
            }

            // $fieldDatas = Field::where('application_id', $filteredData['application_id'])
            //     ->where('status', 1)
            //     ->get();
            // $validationRules = [];
            // foreach ($fieldDatas as $field) {
            //     $rules = [];

            //     if ($field->requiredfield == 1) {
            //         $rules[] = 'required';
            //     }
            //     if ($field->requireuniquevalue == 1) {
            //         $formDataCheck = FormData::where('application_id', $filteredData['application_id'])->get();
            //         $jsonDataArray = [];

            //         foreach ($formDataCheck as $dataceck) {
            //             $jsonData = json_decode($dataceck->data, true);
            //             $jsonDataArray[] = $jsonData;
            //         }
            //         logger($jsonData);
            //         $updateDataArray = json_decode($updateContent->data, true);
            //         logger($updateDataArray);
            //         foreach ($jsonDataArray as $jsonData) {
            //             if ($this->isDataMatch($updateDataArray, $jsonData)) {
            //                 logger('Duplicate data found!');

            //                 return redirect()->back()->with('error', 'Duplicate data found!');
            //             }
            //         }
            //     }

            //     $validationRules[$field->name] = $rules;
            // }
            // $updateContent->validate($validationRules);
            try {
                Formdata::create([
                    'application_id' => $filteredData['application_id'],
                    'data' => $updateContent->data,
                    'userid' => $filteredData['userid'],
                ]);
                logger('Form data created successfully.');
                return back()->with('success', 'Data saved successfully.');
            } catch (\Exception $exception) {
                logger('Failed to save form data:', ['error' => $exception->getMessage()]);
                return back()->with('error', 'Failed to save data.');
            }
        } catch (\Exception $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    private function isDataMatch($array1, $array2)
    {
        $dataMatched = false;

        foreach ($array2 as $key => $value) {
            if (array_key_exists($key, $array1) && @$array1[$key] == $value) {
                $dataMatched = true;
                return true;
            }
        }

        return $dataMatched;
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            //code...
            // dd($id);
            $form = Formdata::find($id);
            $application = Application::find($form->application_id);
            Log::channel('user')->info('Userid ' . auth()->user()->custom_userid . ' , Application Deleted by ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' Application Name -> ' . $application->name);
            Formdata::destroy($id);
            return redirect()->back()->with('success', 'Successfully Deleted.');
            // dd($audit);
        } catch (\Exception $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function userapplication_list($id)
    {
        try {
            $forms = Formdata::where(['userid' => auth()->id(), 'application_id' => $id])->get();
            $application = Application::find($id);
            // $application = Application::with(['roles.permissions'])->findOrFail($id);
            // $roles = $application->rolestable()->first();

            // $roles = Role::whereHas('permissions', function ($query) use ($id) {
            //     $query->where('application_id', $id);
            // })
            //     ->with([
            //         'permissions' => function ($query) use ($id) {
            //             $query->where('application_id', $id);
            //         },
            //     ])
            //     ->get();

            // // Format the permissions into a string for each role
            // $roles->each(function ($role) {
            //     $role->permissions_list = $role->permissions->pluck('name')->implode(', ');
            // });

            $roles = Role::whereHas('permissions', function ($query) use ($id) {
                $query->where('application_id', $id);
            })
                ->with([
                    'permissions' => function ($query) use ($id) {
                        $query->where('application_id', $id);
                    },
                ])
                ->get();

            // Format the permissions into a string for each role
            $roles->each(function ($role) {
                // Use pluck('name')->unique()->implode(', ') to ensure unique permission names
                $role->permissions_list = $role->permissions->pluck('name')->unique()->implode(', ');
            });

            // Collecting unique permissions from all roles
            $uniquePermissions = $roles
                ->flatMap(function ($role) {
                    return $role->permissions->pluck('name');
                })
                ->unique()
                ->sort()
                ->toArray();

            // dd($uniquePermissions);
            // dd($roles);

            $dbfields = Field::where(['application_id' => $application->id, 'status' => 1])
                ->orderBy('forder', 'ASC')
                ->get();

            $fields = [];
            $userid = [];
            for ($i = 0; $i < count($dbfields); $i++) {
                # code...

                if ($dbfields[$i]->access == 'private') {
                    # code...
                    if ($dbfields[$i]->groups != 'null') {
                        array_push($userid, $this->findusers($dbfields[$i]->groups));
                        // if ($dbfields[$i]->rolestable()->first()->group_list != 'null') {
                        //     # code...
                        // }

                        // if ($dbfields[$i]->rolestable()->first()->user_list != 'null') {
                        //     # code...
                        //     array_push($userid, json_decode($dbfields[$i]->rolestable()->first()->user_list));
                        // }

                        $useridfound = 'false';
                        // dd(in_array(auth()->id(), $userid[2]));
                        for ($j = 0; $j < count($userid); $j++) {
                            if (in_array(auth()->id(), $userid[$j])) {
                                $useridfound = 'true';
                            }
                        }

                        if ($useridfound == 'true') {
                            array_push($fields, $dbfields[$i]);
                        }
                        // dd($fields);
                    }
                } else {
                    # code...
                    array_push($fields, $dbfields[$i]);
                }
            }

            $indexing = ApplicationIndexing::where(['userid' => auth()->id(), 'application_id' => $application->id])->first();
            if ($indexing) {
                # code...
                $index = json_decode($indexing->order);
            } else {
                # code...
                $index = null;
            }
            // dd($roles);

            return view('backend.userapplication.applicationlist', compact('forms', 'index', 'id', 'application', 'fields', 'roles', 'uniquePermissions'));
        } catch (\Exception $th) {
            //throw $th;
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function userapplication_edit($id)
    {
        try {
            $form_data = Formdata::find($id);
            $application = Application::find($form_data->application_id);
            $users = User::latest()->get();
            $groups = Group::latest()->get();
            $dbfields = Field::where(['application_id' => $application->id, 'status' => 1])
                ->orderBy('forder', 'ASC')
                ->get();

            $fields = [];
            $userid = [];
            for ($i = 0; $i < count($dbfields); $i++) {
                # code...

                if ($dbfields[$i]->access == 'private') {
                    # code...
                    if ($dbfields[$i]->groups != 'null') {
                        array_push($userid, $this->findusers($dbfields[$i]->groups));
                        // if ($dbfields[$i]->rolestable()->first()->group_list != 'null') {
                        //     # code...
                        // }

                        // if ($dbfields[$i]->rolestable()->first()->user_list != 'null') {
                        //     # code...
                        //     array_push($userid, json_decode($dbfields[$i]->rolestable()->first()->user_list));
                        // }

                        $useridfound = 'false';
                        // dd(in_array(auth()->id(), $userid[2]));
                        for ($j = 0; $j < count($userid); $j++) {
                            if (in_array(auth()->id(), $userid[$j])) {
                                $useridfound = 'true';
                            }
                        }

                        if ($useridfound == 'true') {
                            array_push($fields, $dbfields[$i]);
                        }
                        // dd($fields);
                    }
                } else {
                    # code...
                    array_push($fields, $dbfields[$i]);
                }
            }

            $filledformdata = json_decode($form_data->data, true);
            unset($filledformdata['type123']);
            return view('backend.userapplication.applicationedit', compact('groups', 'id', 'users', 'application', 'fields', 'filledformdata'));
            // dd($id);
        } catch (\Exception $th) {
            //throw $th;
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function findusers($data)
    {
        $array = json_decode($data);
        $userids = [];
        for ($i = 0; $i < count($array); $i++) {
            # code...
            $userarray = Group::find($array[$i]);
            $newarray = json_decode($userarray->userids);
            for ($j = 0; $j < count($newarray); $j++) {
                # code...
                array_push($userids, $newarray[$j]);
            }
            // array_merge($userids, json_decode($userarray->userids));
        }
        return $userids;
    }

    public function userapplication_index($id)
    {
        try {
            $indexing = ApplicationIndexing::where('userid', auth()->id())->first();
            if ($indexing) {
                # code...
                $application = Application::find($id);
                $dbfields = Field::where(['application_id' => $application->id, 'status' => 1])
                    ->orderBy('forder', 'ASC')
                    ->get();

                $fields = [];
                $userid = [];
                for ($i = 0; $i < count($dbfields); $i++) {
                    # code...

                    if ($dbfields[$i]->access == 'private') {
                        # code...
                        if ($dbfields[$i]->groups != 'null') {
                            array_push($userid, $this->findusers($dbfields[$i]->groups));
                            // if ($dbfields[$i]->rolestable()->first()->group_list != 'null') {
                            //     # code...
                            // }

                            // if ($dbfields[$i]->rolestable()->first()->user_list != 'null') {
                            //     # code...
                            //     array_push($userid, json_decode($dbfields[$i]->rolestable()->first()->user_list));
                            // }

                            $useridfound = 'false';
                            // dd(in_array(auth()->id(), $userid[2]));
                            for ($j = 0; $j < count($userid); $j++) {
                                if (in_array(auth()->id(), $userid[$j])) {
                                    $useridfound = 'true';
                                }
                            }

                            if ($useridfound == 'true') {
                                array_push($fields, $dbfields[$i]);
                            }
                            // dd($fields);
                        }
                    } else {
                        # code...
                        array_push($fields, $dbfields[$i]);
                    }
                }
                $i = 0;
                return view('backend.userapplication.userapplicationindex', compact('id', 'fields', 'indexing', 'i'));
            } else {
                # code...

                $application = Application::find($id);
                $dbfields = Field::where(['application_id' => $application->id, 'status' => 1])
                    ->orderBy('forder', 'ASC')
                    ->get();

                $fields = [];
                $userid = [];
                for ($i = 0; $i < count($dbfields); $i++) {
                    # code...

                    if ($dbfields[$i]->access == 'private') {
                        # code...
                        if ($dbfields[$i]->groups != 'null') {
                            array_push($userid, $this->findusers($dbfields[$i]->groups));
                            // if ($dbfields[$i]->rolestable()->first()->group_list != 'null') {
                            //     # code...
                            // }

                            // if ($dbfields[$i]->rolestable()->first()->user_list != 'null') {
                            //     # code...
                            //     array_push($userid, json_decode($dbfields[$i]->rolestable()->first()->user_list));
                            // }

                            $useridfound = 'false';
                            // dd(in_array(auth()->id(), $userid[2]));
                            for ($j = 0; $j < count($userid); $j++) {
                                if (in_array(auth()->id(), $userid[$j])) {
                                    $useridfound = 'true';
                                }
                            }

                            if ($useridfound == 'true') {
                                array_push($fields, $dbfields[$i]);
                            }
                            // dd($fields);
                        }
                    } else {
                        # code...
                        array_push($fields, $dbfields[$i]);
                    }
                }

                $indexing = 'notfound';
                return view('backend.userapplication.userapplicationindex', compact('id', 'fields', 'indexing'));
            }

            // dd($id);
        } catch (\Exception $th) {
            //throw $th;
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function userapplication_index_save(Request $request)
    {
        try {
            // dd($request->all());
            $user = ApplicationIndexing::where(['userid' => $request->userid, 'application_id' => $request->application_id])->first();
            // dd($user);
            if ($user) {
                # code...
                $data = $request->all();
                unset($data['order']);
                unset($data['update']);
                $data['order'] = json_encode($request->order);
                $indexingvalue = ApplicationIndexing::where('userid', auth()->id())->first();

                $indexingvalue->update($data);
                return redirect()->back()->with('success', 'Successfully Updated.');
            } else {
                # code...
                $data = $request->all();
                unset($data['order']);
                $data['order'] = json_encode($request->order);
                ApplicationIndexing::create($data);
                return redirect()
                    ->route('userapplication.list', $request->application_id)
                    ->with('success', 'Successfully Created.');
            }

            // dd($data);
        } catch (\Exception $th) {
            //throw $th;
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
