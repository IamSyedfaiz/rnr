<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\backend\Group;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        try {
            //code...
            $groups = Group::latest()->get();
            $users = User::orderBy('name')->get();
            return view('backend.group.index', compact('groups', 'users'));
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
        try {
            //code...
            return view('backend.applications.create');
        } catch (\Exception $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // dd($request->all());
            $rules = [
                'name' => 'required',
                // 'attachments' => 'required|mimes:pdf,jpg,png|min:5|max:2048',
                'userids' => 'required',
                'user_id' => 'required',
                'status' => 'required',
                // 'description' => 'required',
            ];

            $custommessages = [];

            $this->validate($request, $rules, $custommessages);
            //code...
            $data = $request->all();
            // dd($data);
            unset($data['_token']);
            unset($data['userids']);

            $data['userids'] = json_encode($request->userids);
            $groupName = $request->name;
            $userIds = $request->userids;
            $group = Group::create($data);
            if ($group) {
                foreach ($userIds as $userId) {
                    $user = User::find($userId);
                    if ($user) {
                        $existingGroupIds = json_decode($user->group_id ?? '[]');
                        $newGroupIdString = strval($group->id);
                        if (!in_array($newGroupIdString, $existingGroupIds)) {
                            $existingGroupIds[] = $newGroupIdString;
                        }
                        $updatedGroupIdsJson = json_encode($existingGroupIds);
                        $user->group_id = $updatedGroupIdsJson;
                        $user->save();
                    }
                }
            }
            Log::channel('custom')->info('Userid -> ' . auth()->user()->custom_userid . ' , Group Created by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' Group Name -> ' . $group->name);
            return redirect()->route('group.index')->with('success', 'Group Created.');
        } catch (\Exception $th) {
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
            $group = Group::find($id);
            // dd($application->attachments);
            $users = User::orderBy('name')->get();
            $selectedusers = [];
            if ($group->userids != null) {
                # code...
                $userids = json_decode($group->userids);
                for ($i = 0; $i < count($userids); $i++) {
                    # code...
                    $user = User::find($userids[$i]);
                    array_push($selectedusers, $user);
                }
            }
            // dd($userids, $selectedusers);
            return view('backend.group.edit', compact('group', 'users', 'selectedusers'));
            // dd($audit);
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
        try {
            //code...
            // dd($request->all(), $request->application_id);

            $rules = [
                'name' => 'required',
                // 'attachments' => 'required|mimes:pdf,jpg,png|min:5|max:2048',
                // 'userids' => 'required',
                'user_id' => 'required',
                'status' => 'required',
            ];

            $custommessages = [];

            $this->validate($request, $rules, $custommessages);
            $data = $request->all();
            unset($data['_token']);
            unset($data['_method']);
            unset($data['userids']);

            if ($request->userids != null) {
                # code...
                $data['userids'] = json_encode($request->userids);
            }
            // dd($data);
            $group = Group::find($id);

            $changearray = [];
            if ($group->status == 1) {
                # code...
                $currentstatus = 'Active';
            } else {
                # code...
                $currentstatus = 'InActive';
            }

            $currentarray = [
                'name' => $group->name,
                'status' => $currentstatus,
            ];

            $currentarray['Usernames'] = [];
            // dd(json_decode($group->userids));
            for ($i = 0; $i < count(json_decode($group->userids)); $i++) {
                # code...
                // dd(json_decode($group->userids)[$i]);
                $u = User::find(json_decode($group->userids)[$i]);
                // dd($request->userids, $u);
                array_push($currentarray['Usernames'], $u->name . ' ' . $u->lastname);
            }
            // dd($currentarray);

            if (isset($data['name']) && $group->name != $data['name']) {
                # code...
                $changearray['name'] = $data['name'];
            }
            if (isset($data['userids']) && $group->userids != $data['userids']) {
                $changearray['Usernames'] = [];
                $userIds = $request->userids ?? [];
                if ($group) {
                    foreach ($userIds as $userId) {
                        $user = User::find($userId);
                        // dd($user);
                        if ($user) {
                            $existingGroupIds = json_decode($user->group_id ?? '[]');
                            $newGroupIdString = strval($group->id);
                            if (!in_array($newGroupIdString, $existingGroupIds)) {
                                $existingGroupIds[] = $newGroupIdString;
                            }
                            $updatedGroupIdsJson = json_encode($existingGroupIds);
                            $user->group_id = $updatedGroupIdsJson;
                            $user->save();
                        }
                        array_push($changearray['Usernames'], $user->name . ' ' . $user->lastname);
                    }
                }
                $allUsers = User::all();
                foreach ($allUsers as $user) {
                    if (!empty($userIds)) {
                        if (!in_array(strval($user->id), $userIds)) {
                            $existingGroupIds = json_decode($user->group_id, true) ?? [];
                            $existingGroupIds = array_map('strval', $existingGroupIds);
                            $newGroupIdString = strval($group->id);
                            $updatedGroupIds = array_diff($existingGroupIds, [$newGroupIdString]);
                            $user->group_id = json_encode(array_values($updatedGroupIds));
                            $user->save();
                        }
                    }
                }
                $changearray['UsersChange'] = 'True';
            } else {
                $userIds = $request->userids ?? [];
                if ($group) {
                    if (!empty($userIds)) {
                        // dd($userIds, 'uper');
                        foreach ($userIds as $userId) {
                            $user = User::find($userId);
                            // dd($user);
                            if ($user) {
                                $existingGroupIds = [];
                                $newGroupIdString = strval($group->id);
                                if ($newGroupIdString) {
                                    $existingGroupIds[] = $newGroupIdString;
                                }
                                $updatedGroupIdsJson = json_encode($existingGroupIds);
                                $user->group_id = $updatedGroupIdsJson;
                                $user->save();
                            }
                        }
                    } else {
                        return redirect()->back()->with('error', 'Please Select One User.');
                    }
                    $allUsers = User::all();
                    foreach ($allUsers as $user) {
                        if (!empty($userIds)) {
                            if (!in_array(strval($user->id), $userIds)) {
                                $existingGroupIds = json_decode($user->group_id, true) ?? [];
                                $existingGroupIds = array_map('strval', $existingGroupIds);
                                $newGroupIdString = strval($group->id);
                                $updatedGroupIds = array_diff($existingGroupIds, [$newGroupIdString]);
                                $user->group_id = json_encode(array_values($updatedGroupIds));
                                $user->save();
                            }
                        }
                    }
                }
            }

            if ($group->status != $data['status']) {
                # code...
                if ($data['status'] == 1) {
                    # code...
                    $changearray['status'] = 'Active';
                } else {
                    # code...
                    $changearray['status'] = 'InActive';
                }
            }

            // dd($currentarray, $changearray);

            $group->update($data);
            Log::channel('custom')->info('Userid -> ' . auth()->user()->custom_userid . ' , Group Updated by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' , Group Name -> ' . $group->name . ' , Current Data -> ' . json_encode($currentarray) . ' , Changed Data -> ' . json_encode($changearray));

            // dd($group);
            # code...
            return redirect()->back()->with('success', 'Successfully Group Updated.');
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
            $group = Group::find($id);
            Log::channel('custom')->info('Userid -> ' . auth()->user()->custom_userid . ' , Group Deleted by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' Group Name -> ' . $group->name);
            Group::destroy($id);

            return redirect()->back()->with('success', 'Successfully Group Delete.');
            // dd($audit);
        } catch (\Exception $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
