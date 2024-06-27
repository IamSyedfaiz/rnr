<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\backend\Group;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            //code...
            $users = User::latest()->get();
            // dd($users);
            return view('backend.users.index', compact('users'));
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
        $groups = Group::Orderby('id', 'DESC')->get();
        // dd($groups);
        return view('backend.users.create', compact('groups'));
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
            //code...
            // dd($request->all());

            $rules = [
                'email' => 'required',
                'name' => 'required',
                'custom_userid' => 'required',
                'mobile_no' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'password' => 'required|min:6',
                'repassword' => 'required',
            ];

            $custommessages = [
                'mobile_no.regex' => 'Please Check Mobile Number',
            ];

            $this->validate($request, $rules, $custommessages);

            if ($request->password == $request->repassword) {
                # code...
                $data = $request->all();
                unset($data['_token']);
                unset($data['password']);
                unset($data['repassword']);
                unset($data['group_id']);
                $currentarray = json_encode($data);
                // $data['group_id'] = json_encode($request->group_id);
                $groupId = $request->group_id;
                if ($groupId !== null) {
                    $data['group_id'] = json_encode($groupId);
                } else {
                    unset($data['group_id']);
                }
                $data['password'] = Hash::make($request->password);
                $user = User::create($data);

                if (isset($groupId) && !empty($groupId)) {
                    $userId = $user->id;
                    $targetGroupIds = $request->group_id;
                    $currentGroups = Group::whereJsonContains('userids', $userId)->get();
                    foreach ($currentGroups as $group) {
                        if (!in_array($group->id, $targetGroupIds)) {
                            $userIds = json_decode($group->userids, true);
                            $userIds = array_filter($userIds, function ($id) use ($userId) {
                                return $id != $userId;
                            });
                            $group->userids = json_encode(array_values($userIds));
                            $group->save();
                        }
                    }
                    foreach ($targetGroupIds as $groupId) {
                        $group = Group::find($groupId);
                        if ($group) {
                            $userIds = json_decode($group->userids, true);
                            if (!is_array($userIds)) {
                                $userIds = [];
                            }
                            if (!in_array((string) $userId, $userIds)) {
                                $userIds[] = (string) $userId;
                                $group->userids = $userIds;
                                $group->save();
                            }
                        }
                    }
                }
                // dd($data);
                Log::channel('custom')->info('Userid -> ' . auth()->user()->custom_userid . ' , User Created by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' User Name -> ' . $user->name . ' Data -> ' . @$currentarray);

                return redirect()->route('users.index')->with('success', 'User Created');
            } else {
                # code...
                throw new \Exception('Password Does Not Matched');
            }
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
        //
        // dd($id);
        $user = User::find($id);
        $groups = Group::Orderby('id', 'DESC')->get();
        $groupids = json_decode($user->group_id);
        // dd($groupids);
        return view('backend.users.edit', compact('user', 'groups', 'groupids'));
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
            // dd($request->all());
            $rules = [
                'email' => 'required',
                'name' => 'required',
                'custom_userid' => 'required',

                'mobile_no' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                'password' => 'required_with:password_confirmation|confirmed',
            ];

            $custommessages = [
                // 'custom_userid' => 'User Id Is Already Exists.',
                'password.confirmed' => 'Password Does Not Match',
            ];

            $this->validate($request, $rules, $custommessages);

            $data = $request->all();
            unset($data['_token']);
            unset($data['password']);
            unset($data['password_confirmation']);
            // dd($id);
            if ($request->password) {
                # code...
                $data['password'] = Hash::make($request->password);
            }
            // dd($data);
            $user = User::find($id);

            $changearray = [];
            if ($user->status == 1) {
                # code...
                $currentstatus = 'Active';
            } else {
                # code...
                $currentstatus = 'InActive';
            }

            $currentarray = [
                'firstname' => $user->name,
                'lastname' => $user->lastname,
                'mobile_no' => $user->mobile_no,
                'email' => $user->email,
                'ssh_key' => $user->ssh_key,
                'remarks' => $user->remarks,
                'status' => $currentstatus,
            ];

            if (isset($data['name']) && $user->name != $data['name']) {
                # code...
                $changearray['firstname'] = $data['name'];
            }

            if (isset($data['lastname']) && $user->lastname != $data['lastname']) {
                # code...
                $changearray['lastname'] = $data['lastname'];
            }

            if (isset($data['mobile_no']) && $user->mobile_no != $data['mobile_no']) {
                # code...
                $changearray['mobile_no'] = $data['mobile_no'];
            }

            if (isset($data['email']) && $user->email != $data['email']) {
                # code...
                $changearray['email'] = $data['email'];
            }

            if (isset($data['ssh_key']) && $user->ssh_key != $data['ssh_key']) {
                # code...
                $changearray['ssh_key'] = $data['ssh_key'];
            }

            if (isset($data['remarks']) && $user->remarks != $data['remarks']) {
                # code...
                $changearray['remarks'] = $data['remarks'];
            }

            if ($user->status != $data['status']) {
                # code...
                if ($data['status'] == 1) {
                    # code...
                    $changearray['status'] = 'Active';
                } else {
                    # code...
                    $changearray['status'] = 'InActive';
                }
            }

            // if (isset($data['group_id']) && $user->group_id != $data['group_id']) {
            //     # code...
            //     // dd($data);
            //     $changearray['Groupnames'] = [];
            //     for ($i = 0; $i < count($request->group_id); $i++) {
            //         # code...
            //         $u = Group::find($request->group_id[$i]);
            //         // dd($id, $u);

            //         if ($u) {
            //             // Decode the userids JSON to an array
            //             $userIds = json_decode($u->userids, true);

            //             // Ensure $userIds is an array
            //             if (!is_array($userIds)) {
            //                 $userIds = [];
            //             }

            //             // Add the new user ID if it isn't already in the array
            //             if (!in_array($id, $userIds)) {
            //                 $userIds[] = $id;
            //             }

            //             // Encode the array back to JSON and save
            //             $u->userids = json_encode($userIds);
            //             $u->save();

            //             // Debugging output
            //             // dd($u);
            //         } else {
            //             // Handle the case where the group is not found
            //             dd("Group with ID $id not found");
            //         }
            //         // dd($ud);

            //         if (!$u) {
            //             # code...
            //             $changearray['Groupnames'] = null;
            //         } else {
            //             array_push($changearray['Groupnames'], $u->name);
            //         }
            //     }
            //     $changearray['GroupChange'] = 'True';
            //     // dd($changearray);
            // } else {
            //     $user->group_id = null;
            //     $user->save();
            // }
            if (isset($data['group_id']) && $user->group_id != $data['group_id']) {
                $changearray['Groupnames'] = [];

                // Get user ID and target groups from the request
                $userId = $id;
                $targetGroupIds = $request->group_id;

                // Get all groups the user is currently a member of
                $currentGroups = Group::whereJsonContains('userids', $userId)->get();

                // Process each current group to remove the user if not in target groups
                foreach ($currentGroups as $group) {
                    if (!in_array($group->id, $targetGroupIds)) {
                        $userIds = json_decode($group->userids, true);
                        $userIds = array_filter($userIds, function ($id) use ($userId) {
                            return $id != $userId;
                        });
                        $group->userids = json_encode(array_values($userIds));
                        $group->save();

                        $changearray['Groupnames'][] = $group->name;
                    }
                }

                // Process each target group to add the user if not already a member
                foreach ($targetGroupIds as $groupId) {
                    $group = Group::find($groupId);
                    if ($group) {
                        $userIds = json_decode($group->userids, true);
                        if (!is_array($userIds)) {
                            $userIds = [];
                        }
                        if (!in_array($userId, $userIds)) {
                            $userIds[] = $userId;
                            $group->userids = json_encode($userIds);
                            $group->save();

                            $changearray['Groupnames'][] = $group->name;
                        }
                    }
                }

                // If targetGroupIds is empty, set user's group_id to null and remove user from all groups
                if (empty($targetGroupIds)) {
                    // Set user's group_id to null
                    $user->group_id = null;
                    $user->save();

                    // Remove user from all current groups
                    foreach ($currentGroups as $group) {
                        $userIds = json_decode($group->userids, true);
                        $userIds = array_filter($userIds, function ($id) use ($userId) {
                            return $id != $userId;
                        });
                        $group->userids = json_encode(array_values($userIds));
                        $group->save();

                        $changearray['Groupnames'][] = $group->name;
                    }
                }

                $changearray['GroupChange'] = 'True';
                // Debugging output
                // dd($changearray);
            } else {
                // If the group_id has not changed, output the current group_id
                $currentGroups = Group::whereJsonContains('userids', $id)->get();
                foreach ($currentGroups as $group) {
                    $userIds = json_decode($group->userids, true);
                    $userIds = array_filter($userIds, function ($userId) use ($id) {
                        return $userId != $id;
                    });
                    $group->userids = json_encode(array_values($userIds));
                    $group->save();

                    $changearray['Groupnames'][] = $group->name;
                }
                $user->group_id = null;
                $user->save();
            }
            $user->update($data);
            Log::channel('custom')->info('Userid ' . auth()->user()->custom_userid . ' , User Edited by ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' User Name -> ' . $user->name . ' Current Data -> ' . json_encode($currentarray) . ' Changed Data -> ' . json_encode($changearray));

            return redirect()->back()->with('success', 'User Updated.');
        } catch (\Throwable $th) {
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
            $user = User::find($id);
            Log::channel('custom')->info('Userid -> ' . auth()->user()->custom_userid . ' , User Deleted by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' User Name -> ' . $user->name);
            User::destroy($id);

            return redirect()->back()->with('success', 'Successfully User Delete.');
            // dd($audit);
        } catch (\Exception $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
