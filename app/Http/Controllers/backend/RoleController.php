<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\backend\Application;
use App\Models\backend\Group;
use App\Models\backend\Role;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
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
            $applications = Application::where('status', 1)
                ->latest()
                ->get();
            return view('backend.role.index', compact('applications'));
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
            $data = $request->all();
            unset($data['_token']);
            unset($data['user_list']);
            unset($data['group_list']);

            $data['user_list'] = json_encode($request->user_list);
            $data['group_list'] = json_encode($request->group_list);
            $application = Application::find($request->application_id);
            // dd($application);
            $role1 = Role::create($data);
            Log::channel('custom')->info('Userid -> ' . auth()->user()->custom_userid . ' , Role Created by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' Application Name -> ' . $application->name);

            return redirect()
                ->route('role.edit', $role1->id)
                ->with('success', 'Successfully Roles Created.');
            if ($request->user_list == null && $request->group_list == null) {
                # code...
                throw new \Exception('Select Atleast User or Group.');
            }
        } catch (\Exception $th) {
            //throw $th;
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
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
            $role = Role::find($id);
            // dd($role);
            $application = Application::find($role->application_id);
            if ($role != null) {
                # code...
                $applicationrole = $role;
            } else {
                # code...
                $applicationrole = null;
            }

            $selectedgroups = [];

            // dd($role->group_list);
            if ($role != null && $role->group_list != 'null') {
                $groupids = json_decode($role->group_list);
                # code...
                // dd($groupids);
                for ($i = 0; $i < count($groupids); $i++) {
                    # code...
                    $group = Group::find($groupids[$i]);
                    array_push($selectedgroups, $group);
                }
            }

            $selectedusers = [];
            if ($role != null && $role->user_list != null) {
                $userids = json_decode($role->user_list);
                # code...
                for ($i = 0; $i < count($userids); $i++) {
                    # code...
                    $user = User::find($userids[$i]);
                    array_push($selectedusers, $user);
                }
            }
            // dd($selectedusers);

            $users = User::where('status', 1)
                ->latest()
                ->get();
            $groups = Group::where('status', 1)
                ->latest()
                ->get();

            return view('backend.role.edit', compact('selectedgroups', 'selectedusers', 'application', 'applicationrole', 'users', 'groups'));
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
    public function update(Request $request, $id)
    {
        try {
            //code...
            // dd($request->all());
            $role = Role::find($id);
            $application = Application::find($request->application_id);

            // dd($role);
            if ($role) {
                # code...
                $data = $request->all();
                unset($data['_token']);
                unset($data['_method']);
                unset($data['user_list']);
                unset($data['group_list']);

                if (!$request->import) {
                    # code...
                    $data['import'] = 0;
                }

                if (!$request->create) {
                    # code...
                    $data['create'] = 0;
                }

                if (!$request->read) {
                    # code...
                    $data['read'] = 0;
                }

                if (!$request->update) {
                    # code...
                    $data['update'] = 0;
                }

                if (!$request->delete) {
                    # code...
                    $data['delete'] = 0;
                }

                if ($request->user_list) {
                    # code...
                    $data['user_list'] = json_encode($request->user_list);
                }

                if ($request->group_list) {
                    # code...
                    $data['group_list'] = json_encode($request->group_list);
                }
                // dd($data);
                $changearray = [];
                if ($role->import == 1) {
                    # code...
                    $currentimport = 'Active';
                } else {
                    # code...
                    $currentimport = 'InActive';
                }

                if ($role->create == 1) {
                    # code...
                    $currentcreate = 'Active';
                } else {
                    # code...
                    $currentcreate = 'InActive';
                }

                if ($role->read == 1) {
                    # code...
                    $currentread = 'Active';
                } else {
                    # code...
                    $currentread = 'InActive';
                }

                if ($role->update == 1) {
                    # code...
                    $currentupdate = 'Active';
                } else {
                    # code...
                    $currentupdate = 'InActive';
                }

                if ($role->delete == 1) {
                    # code...
                    $currentdelete = 'Active';
                } else {
                    # code...
                    $currentdelete = 'InActive';
                }

                $currentarray = [
                    'import' => $currentimport,
                    'create' => $currentcreate,
                    'read' => $currentread,
                    'update' => $currentupdate,
                    'delete' => $currentdelete,
                    'application' => $application->name,
                ];

                if ($role->import != $data['import']) {
                    # code...
                    if ($data['import'] == 1) {
                        # code...
                        $changearray['import'] = 'Active';
                    } else {
                        # code...
                        $changearray['import'] = 'InActive';
                    }
                }

                if ($role->create != $data['create']) {
                    # code...
                    if ($data['create'] == 1) {
                        # code...
                        $changearray['create'] = 'Active';
                    } else {
                        # code...
                        $changearray['create'] = 'InActive';
                    }
                }

                if ($role->read != $data['read']) {
                    # code...
                    if ($data['read'] == 1) {
                        # code...
                        $changearray['read'] = 'Active';
                    } else {
                        # code...
                        $changearray['read'] = 'InActive';
                    }
                }

                if ($role->update != $data['update']) {
                    # code...
                    if ($data['update'] == 1) {
                        # code...
                        $changearray['update'] = 'Active';
                    } else {
                        # code...
                        $changearray['update'] = 'InActive';
                    }
                }

                if ($role->delete != $data['delete']) {
                    # code...
                    if ($data['delete'] == 1) {
                        # code...
                        $changearray['delete'] = 'Active';
                    } else {
                        # code...
                        $changearray['delete'] = 'InActive';
                    }
                }

                if (isset($data['user_list']) && $role->user_list != $data['user_list']) {
                    # code...
                    // dd($data);
                    $changearray['Usernames'] = [];
                    for ($i = 0; $i < count($request->user_list); $i++) {
                        # code...
                        $u = User::find($request->user_list[$i]);
                        // dd($request->userids, $u);
                        array_push($changearray['Usernames'], $u->name . ' ' . $u->lastname);
                    }
                    $changearray['UsersChange'] = 'True';
                    // dd($changearray);
                }

                if (isset($data['group_list']) && $role->group_list != $data['group_list']) {
                    # code...
                    // dd($data);
                    $changearray['Groupnames'] = [];
                    for ($i = 0; $i < count($request->group_list); $i++) {
                        # code...
                        $u = Group::find($request->group_list[$i]);
                        // dd($request->userids, $u);
                        array_push($changearray['Groupnames'], $u->name);
                    }
                    $changearray['GroupChange'] = 'True';
                    // dd($changearray);
                }
                // dd($changearray);

                $role->update($data);
                Log::channel('custom')->info('Userid -> ' . auth()->user()->custom_userid . ' , Role Edited by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' Application Name -> ' . $application->name . ' Current Data -> ' . json_encode($currentarray) . ' Changed Data -> ' . json_encode($changearray));

                return redirect()
                    ->back()
                    ->with('success', 'Successfully Roles Updated.');
            } else {
                # code...
                if ($request->user_list == null && $request->group_list == null) {
                    # code...
                    throw new \Exception('Select Atleast User or Group.');
                } else {
                    # code...
                    $data = $request->all();
                    unset($data['_token']);
                    unset($data['_method']);
                    unset($data['user_list']);
                    unset($data['group_list']);

                    $data['user_list'] = json_encode($request->user_list);
                    $data['group_list'] = json_encode($request->group_list);
                    Role::create($data);
                    Log::channel('custom')->info('Userid ' . auth()->user()->custom_userid . ' , Role Edited by ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' Application Name -> ' . $application->name);

                    return redirect()
                        ->route('role.index')
                        ->with('success', 'Successfully Roles Created.');
                }
            }
        } catch (\Exception $th) {
            //throw $th;
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
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
            $role = Role::find($id);
            Log::channel('user')->info('Userid ' . auth()->user()->custom_userid . ' , Role Deleted by ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' Role Name -> ' . $role->name);
            Role::destroy($id);
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
}
