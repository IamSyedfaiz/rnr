<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\backend\Field;
use App\Models\User;
use App\Models\backend\Group;
use App\Models\backend\Application;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        //code...
        $field = Field::find($id);
        $groups = Group::where(['status' => 1])
            ->latest()
            ->get();
        $users = User::where('status', 1)->latest()->get();

        return view('backend.field.create', compact('field', 'groups', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $rules = [
        //     'name' => 'required.unique:fields',
        //     'type' => 'required',
        //     'status' => 'required',
        // ];

        // $custommessages = [
        //     'name.required' => 'The name field is required.',
        //     'name.unique' => 'The name field must be unique.',
        // ];
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:fields',
            'type' => 'required',
            'status' => 'required',
            'requiredfield' => 'nullable',
            'requireuniquevalue' => 'nullable',
            'keyfield' => 'nullable',
            'access' => 'required',
            'application_id' => 'required',
        ]);

        try {
            $data = $validator->validate();
            // $this->validate($request, $rules, $custommessages);
            if (isset($data['name'])) {
                $data['name'] = str_replace(' ', '_', $data['name']);
            }
            //code...
            // $data = $request->all();
            // dd($data);
            unset($data['applicationid']);

            $application = Application::find($request->application_id);
            // dd($application);
            $applicationfields = Field::where('application_id', $application->id)->get();
            for ($i = 0; $i < count($applicationfields); $i++) {
                # code...
                if ($applicationfields[$i]->keyfield == 1) {
                    # code...
                    // dd('prateek');
                    if (isset($request->keyfield) && $request->keyfield == 1) {
                        # code...
                        throw new \Exception('Already Assign Key field');
                    }
                }
            }

            unset($data['_token']);
            if ($request->valuelistvalue) {
                # code...
                unset($data['valuelistvalue']);
                $data['valuelistvalue'] = json_encode($request->valuelistvalue);
            }

            if ($request->user_list) {
                # code...
                unset($data['user_list']);
                $data['user_list'] = json_encode($request->user_list);
            }

            if ($request->group_list) {
                # code...
                unset($data['group_list']);
                $data['group_list'] = json_encode($request->group_list);
            }

            // dd($data);
            if ($request->groups) {
                # code...
                if (count($request->groups) > 0) {
                    // dd($request->groups);
                    # code...
                    $data['groups'] = json_encode($request->groups);
                }
            }

            if ($request->users) {
                # code...
                if (count($request->users) > 0) {
                    // dd($request->groups);
                    # code...
                    $data['users'] = json_encode($request->users);
                }
            }

            if ($request->access == 'public') {
                # code...
                $data['groups'] = null;
            }
            // dd($data);
            $data['forder'] = count($applicationfields) + 1;
            // dd($data);
            $field = Field::create($data);
            Log::channel('custom')->info('Userid -> ' . auth()->user()->custom_userid . ' , Field Created by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' Field Name -> ' . $field->name . ' Field Type -> ' . $field->type);

            // dd($field, $field->id);
            if ($application->fields == null) {
                # code...
                $fieldid = [];
                array_push($fieldid, $field->id);
                $application->fields = json_encode($fieldid);
                $application->save();
            } else {
                # code...
                $fieldid = json_decode($application->fields);
                array_push($fieldid, $field->id);
                $application->fields = json_encode($fieldid);
                $application->save();
            }

            return redirect()
                ->route('application.edit', $application->id)
                ->with(['success' => 'Field Created.', 'field' => 'active']);
        } catch (\Exception $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //code...
        $field = Field::find($id);
        $groups = Group::where(['status' => 1])
            ->latest()
            ->get();
        $users = User::where('status', 1)->latest()->get();
        $application = Application::find($id);

        return view('backend.field.create', compact('field', 'groups', 'users', 'application'));
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
            $field = Field::find($id);
            $groups = Group::where(['status' => 1])
                ->latest()
                ->get();

            $users = User::where('status', 1)->latest()->get();

            $selectedgroups = [];
            if ($field->groups != null) {
                $groupids = json_decode($field->groups);
                # code...
                for ($i = 0; $i < count($groupids); $i++) {
                    # code...
                    $group = Group::find($groupids[$i]);
                    array_push($selectedgroups, $group);
                }
            }

            $selectedusers = [];
            if ($field->users != null) {
                $userids = json_decode($field->users);
                // dd($userids);
                # code...
                for ($i = 0; $i < count($userids); $i++) {
                    # code...
                    $user = User::find($userids[$i]);
                    array_push($selectedusers, $user);
                }
            }
            // dd($selectedusers, $selectedgroups);

            if ($field->user_list != null) {
                # code...
                $userid = json_decode($field->user_list);
                $userlist = [];
                for ($i = 0; $i < count($userid); $i++) {
                    # code...
                    $user = User::find($userid[$i]);
                    array_push($userlist, $user);
                }
            } else {
                $userlist = null;
            }

            // dd($userlist);

            if ($field->group_list != null) {
                # code...
                $groupid = json_decode($field->group_list);
                $grouplist = [];
                for ($i = 0; $i < count($groupid); $i++) {
                    # code...
                    $group = Group::find($groupid[$i]);
                    array_push($grouplist, $group);
                }
            } else {
                $grouplist = null;
            }
            // dd($userlist, $field);
            return view('backend.field.edit', compact('userlist', 'selectedusers', 'grouplist', 'field', 'groups', 'selectedgroups', 'users'));
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
    public function update(Request $request, $id)
    {
        //code...
        $rules = [
            'name' => 'required',
            // 'forder' => 'required',
            'type' => 'required',
            'status' => 'required',
        ];

        $custommessages = [
            'forder' => 'Order is required',
        ];

        $this->validate($request, $rules, $custommessages);

        $data = $request->all();
        if (isset($data['name'])) {
            $data['name'] = str_replace(' ', '_', $data['name']);
        }
        unset($data['_token']);
        unset($data['_method']);
        unset($data['groups']);

        if ($request->valuelistvalue) {
            # code...
            unset($data['valuelistvalue']);
            $data['valuelistvalue'] = json_encode($request->valuelistvalue);
        }

        if ($request->user_list) {
            # code...
            unset($data['user_list']);
            $data['user_list'] = json_encode($request->user_list);
        }

        if ($request->group_list) {
            # code...
            unset($data['group_list']);
            $data['group_list'] = json_encode($request->group_list);
        }

        // dd($data);
        if ($request->groups) {
            # code...
            if (count($request->groups) > 0) {
                // dd($request->groups);
                # code...
                $data['groups'] = json_encode($request->groups);
            }
        }

        if ($request->access == 'public') {
            # code...
            $data['groups'] = null;
            $data['users'] = null;
        }
        // dd($data);

        //for logs
        $field = Field::find($id);
        // dd($field);
        $changearray = [];
        if ($field->status == 1) {
            # code...
            $currentstatus = 'Active';
        } else {
            # code...
            $currentstatus = 'InActive';
        }

        if ($field->requiredfield == 1) {
            # code...
            $currentrequiredfield = 'Active';
        } else {
            # code...
            $currentrequiredfield = 'InActive';
        }

        if ($field->requireuniquevalue == 1) {
            # code...
            $currentrequireuniquevalue = 'Active';
        } else {
            # code...
            $currentrequireuniquevalue = 'InActive';
        }

        if ($field->keyfield == 1) {
            # code...
            $currentkeyfield = 'Active';
        } else {
            # code...
            $currentkeyfield = 'InActive';
        }
        $currentarray = [
            'name' => $field->name,
            'type' => $field->type,
            'status' => $field->currentstatus,
            'requiredfield' => $field->currentrequiredfield,
            'requireuniquevalue' => $field->currentrequireuniquevalue,
            'keyfield' => $field->keyfield,
            'access' => $field->access,
            'description' => $field->description,
        ];

        if ($field->name != $data['name']) {
            # code...
            $changearray['name'] = $data['name'];
        }

        if ($field->type != $data['type']) {
            # code...
            $changearray['type'] = $data['type'];
        }

        if ($field->access != $data['access']) {
            # code...
            $changearray['access'] = $data['access'];
        }

        if ($field->status != $data['status']) {
            # code...
            if ($data['status'] == 1) {
                # code...
                $changearray['status'] = 'Active';
            } else {
                # code...
                $changearray['status'] = 'InActive';
            }
        }

        if (isset($data['requiredfield']) && $field->requiredfield != $data['requiredfield']) {
            # code...
            if ($data['requiredfield'] == 1) {
                # code...
                $changearray['requiredfield'] = 'Active';
            } else {
                # code...
                $changearray['requiredfield'] = 'InActive';
            }
        }

        if (isset($data['requireuniquevalue']) && $field->requireuniquevalue != $data['requireuniquevalue']) {
            # code...
            if ($data['requireuniquevalue'] == 1) {
                # code...
                $changearray['requireuniquevalue'] = 'Active';
            } else {
                # code...
                $changearray['requireuniquevalue'] = 'InActive';
            }
        }

        if (isset($data['keyfield']) && $field->keyfield != $data['keyfield']) {
            # code...
            if ($data['keyfield'] == 1) {
                # code...
                $changearray['keyfield'] = 'Active';
            } else {
                # code...
                $changearray['keyfield'] = 'InActive';
            }
        }

        if (isset($data['description']) && $field->description != $data['description']) {
            # code...
            $changearray['description'] = $data['description'];
        }

        $field->update($data);
        Log::channel('custom')->info('Userid -> ' . auth()->user()->custom_userid . ' , Field Edited by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' Field Name -> ' . $field->name . ' Current Data -> ' . json_encode($currentarray) . ' Changed Data -> ' . json_encode($changearray));

        return redirect()
            ->back()
            ->with(['success' => 'Successfully Field Edit.']);
        try {
        } catch (\Exception $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            //code...
            // dd($id);
            $field = Field::find($id);
            $application = Application::find($field->application_id);
            $fieldid = json_decode($application->fields);
            // dd($fieldid, $fieldid[0]);
            for ($i = 0; $i < count($fieldid); $i++) {
                # code...
                if ($fieldid[$i] == $id) {
                    # code...
                    unset($fieldid[$i]);
                }
            }
            // dd($fieldid);
            $application->fields = json_encode($fieldid);
            $application->save();
            Log::channel('custom')->info('Userid ' . auth()->user()->custom_userid . ' , Field Deleted by ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' Field Name -> ' . $field->name);
            Field::destroy($id);
            return redirect()->back()->with('success', 'Successfully Field Delete.');
            // dd($audit);
        } catch (\Exception $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
