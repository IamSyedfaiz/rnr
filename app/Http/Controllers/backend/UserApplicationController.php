<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
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

class UserApplicationController extends Controller
{
    use WorkflowTraits;

    public function index()
    {
        try {
            //code...
            $loggedinuser = auth()->id();
            // dd($userid);
            $application = Application::where('status', 1)
                ->latest()
                ->get();

            $userapplication = [];
            $userid = [];
            // dd($loggedinuser);

            for ($i = 0; $i < count($application); $i++) {
                # code...
                if ($application[$i]->rolestable()->first() != 'null' && $application[$i]->rolestable()->first() != null) {
                    dd($application[$i]->rolestable()->first()->group_list);
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
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
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
            //code...
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
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
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
    public function update(Request $request, $id)
    {
        $data = $request->all();
        dd($data);
        unset($data['_token']);
        unset($data['_method']);
        unset($data['userid']);
        unset($data['formdataid']);
        foreach (request()->allFiles() as $key => $value) {
            if ($value->getSize() > 2e6) {
                # code...
                throw new Exception('File Size is more then 2 mb');
            } else {
                # code...
                unset($data[$key]);
                $filename = rand() . $value->getClientOriginalName();
                $value->move(public_path('files'), $filename);
                $data[$key] = $filename;
            }
        }

        $application = Application::find($id);
        $fieldDatas = Field::where('application_id', $application->id)
            ->where('status', 1)
            ->get();

        // validate start
        $validationRules = [];
        foreach ($fieldDatas as $field) {
            $rules = [];

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
                        return redirect()
                            ->back()
                            ->with('error', 'Duplicate data found!');
                    }
                }
            }

            $validationRules[$field->name] = $rules;
        }
        $request->validate($validationRules);
        // validate end

        $authUserId = auth()->user()->id;

        $groups = Group::where('status', 1)
            ->whereJsonContains('userids', (string) $authUserId)
            ->get();

        $groupIds = $groups->pluck('id');
        $notifications = Notification::where('active', 'Y')
            ->where('recurring', 'instantly')
            ->where(function ($query) use ($authUserId, $groupIds) {
                $query->whereJsonContains('user_list', (string) $authUserId)->orWhereJsonContains('group_list', $groupIds);
            })
            ->get();
        $notificationIds = $notifications->pluck('id')->toArray();

        $filterCriterias = FilterCriteria::whereIn('notification_id', $notificationIds)->get();
        dd($filterCriterias);
        $sendEmail = false;

        if ($filterCriterias) {
            foreach ($filterCriterias as $key => $filterCriteria) {
                // dd(1);
                if ($filterCriteria->filter_operator == 'C' && $filterCriteria->filter_value == request()->input($filterCriteria->field->name)) {
                    $sendEmail = true;
                    logger('--c---');
                } elseif ($filterCriteria->filter_operator == 'DNC' && $filterCriteria->filter_value !== request()->input($filterCriteria->field->name)) {
                    $sendEmail = true;
                    logger('--DNC---');
                } elseif ($filterCriteria->filter_operator == 'E' && $filterCriteria->filter_value === request()->input($filterCriteria->field->name)) {
                    $sendEmail = true;
                    logger('--e---');
                } elseif ($filterCriteria->filter_operator == 'DNE' && $filterCriteria->filter_value == request()->input($filterCriteria->field->name)) {
                    $sendEmail = true;
                    logger('--DNE---');
                } elseif ($filterCriteria->filter_operator == 'CH' && $filterCriteria->filter_value == request()->input($filterCriteria->field->name)) {
                    $sendEmail = true;
                    logger('--DNE---');
                } elseif ($filterCriteria->filter_operator == 'CT' && $filterCriteria->filter_value == request()->input($filterCriteria->field->name)) {
                    $sendEmail = true;
                    logger('--DNE---');
                } elseif ($filterCriteria->filter_operator == 'CF' && $filterCriteria->filter_value == request()->input($filterCriteria->field->name)) {
                    $sendEmail = true;
                    logger('--DNE---');
                }
                if ($sendEmail) {
                    logger('Email sent!');
                    $selectedGroups = [];
                    if ($filterCriteria->notification->group_list != 'null') {
                        $groupIds = json_decode($filterCriteria->notification->group_list);

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
                    if ($filterCriteria->notification->user_list != 'null') {
                        $UserIds = json_decode($filterCriteria->notification->user_list);

                        if ($UserIds) {
                            foreach ($UserIds as $UserId) {
                                $User = User::find($UserId);

                                if ($User) {
                                    $selectedUsers[] = $User->email;
                                }
                            }
                        }
                    }

                    // foreach ($userGroups as $value) {
                    //     logger($value);
                    // }
                    // foreach ($selectedUsers as $value) {
                    //     logger('--user');
                    //     logger($value);
                    // }
                    $template = $filterCriteria->notification->body;
                    $Formdata01 = Formdata::where('application_id', $filterCriteria->notification->application_id)->get();

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
                            Mail::send('email.loginmail', @$replaceddata, function ($msg) use ($recipient, $filterCriteria) {
                                $msg->from(env('MAIL_FROM_ADDRESS'));
                                $msg->to($recipient, env('MAIL_FROM_NAME'));
                                $msg->subject($filterCriteria->notification->subject);
                            });
                        }
                    }
                    if ($selectedUsers) {
                        foreach ($selectedUsers as $recipient) {
                            Mail::send('email.loginmail', @$replaceddata, function ($msg) use ($recipient, $filterCriteria) {
                                $msg->from(env('MAIL_FROM_ADDRESS'));
                                $msg->to($recipient, env('MAIL_FROM_NAME'));
                                $msg->subject($filterCriteria->notification->subject);
                            });
                        }
                    }
                }
            }
        }

        // dd($filterCriterias);

        if (isset($request->formdataid)) {
            $data1['data'] = json_encode($data);
            $data1['userid'] = $request->userid;
            $data1['application_id'] = $id;
            $formdata = Formdata::find($request->formdataid);
            $currentarray = $formdata->data;
            $changearray = $data1['data'];
            $formdata->update($data1);
            Log::channel('user')->info('Userid -> ' . auth()->user()->custom_userid . ' , Application Edited by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' Application Name -> ' . $application->name . ' Current Data -> ' . $currentarray . ' Change Data -> ' . $changearray);

            return redirect()
                ->back()
                ->with('success', 'Form Updated.');
        } else {
            # code...
            $data1['data'] = json_encode($data);
            $data1['userid'] = $request->userid;
            $data1['application_id'] = $id;
            Log::channel('user')->info('Application Created by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' Application Name -> ' . $application->name . ' Current Data -> ' . $data1['data']);
            Formdata::create($data1);
            return redirect()
                ->route('userapplication.list', $id)
                ->with('success', 'Form Saved.');
        }
        try {
        } catch (\Exception $th) {
            //throw $th;
            //throw $th;
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
        }
    }
    private function isDataMatch($array1, $array2)
    {
        $dataMatched = false;

        foreach ($array2 as $key => $value) {
            // logger("=========");
            // logger(json_encode(@$array1[$key]));
            // logger(json_encode($value));
            // logger("========");

            // logger("Inside First ELSE");
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
            return redirect()
                ->back()
                ->with('success', 'Successfully Deleted.');
            // dd($audit);
        } catch (\Exception $th) {
            //throw $th;
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
        }
    }

    public function userapplication_list($id)
    {
        try {
            //code...

            $forms = Formdata::where(['userid' => auth()->id(), 'application_id' => $id])->get();
            $application = Application::find($id);
            $roles = $application->rolestable()->first();
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

            // dd($index, $fields);
            // dd($fields);
            // dd($application->rolestable()->get());
            // dd($forms, $fields, $index);
            return view('backend.userapplication.applicationlist', compact('forms', 'index', 'id', 'application', 'roles', 'fields'));
        } catch (\Exception $th) {
            //throw $th;
            //throw $th;
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
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
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
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
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
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
                return redirect()
                    ->back()
                    ->with('success', 'Successfully Updated.');
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
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
        }
    }
}
