<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\backend\Application;
use App\Models\backend\Group;
use App\Models\backend\Permission;
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
            $applications = Application::where('status', 1)->latest()->get();
            $roles = Role::all();

            return view('backend.role.index', compact('applications', 'roles'));
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
            $data['user_id'] = $request->input('user_id');
            // dd($data);
            $permissions = $request->input('permissions');
            $role1 = Role::create($data);
            // dd($role1);
            if ($permissions) {

                $permissionsToInsert = [];
                if ($role1) {

                    foreach ($permissions as $applicationId => $applicationPermissions) {
                        foreach ($applicationPermissions as $permissionId => $permissionData) {
                            $value = $permissionData['value'];
                            preg_match('/\[(\d+)\]\[(\d+)\]/', $value, $matches);
                            if (isset($matches[1]) && isset($matches[2])) {
                                $extractedPermissionId = $matches[2];
                                $role1->permissions()->attach($extractedPermissionId, ['application_id' => $applicationId]);
                            }
                        }
                    }
                } else {
                    return redirect()->back()->with('error', 'Role creation failed');

                }
            } else {
                return redirect()->back()->with('error', 'Please select permissions');

            }



            Log::channel('custom')->info('Userid -> ' . auth()->user()->custom_userid . ' , Role Created by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname);

            return redirect()
                ->route('role.index')
                ->with('success', 'Successfully Roles Created.');
            if ($request->user_list == null && $request->group_list == null) {
                # code...
                throw new \Exception('Select Atleast User or Group.');
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
        try {
            //code...
            $role = Role::find($id);

            // dd($role);
            $application = Application::find($role->application_id);

            $selectedgroups = [];

            // dd($role->group_list);
            if ($role != null && $role->group_list !== null && $role->group_list !== 'null') {
                $groupids = json_decode($role->group_list);
                # code...
                // dd($groupids);
                if ($groupids !== null) {
                    for ($i = 0; $i < count($groupids); $i++) {
                        # code...
                        $group = Group::find($groupids[$i]);
                        array_push($selectedgroups, $group);
                    }
                }
            }

            $selectedusers = [];
            if ($role != null && $role->user_list != null) {
                $userids = json_decode($role->user_list);
                # code...
                if ($userids !== null) {
                    for ($i = 0; $i < count($userids); $i++) {
                        # code...
                        $user = User::find($userids[$i]);
                        array_push($selectedusers, $user);
                    }
                }
            }

            $users = User::where('status', 1)->latest()->get();
            $groups = Group::where('status', 1)->latest()->get();
            $applications = Application::where('status', 1)->latest()->get();
            $permissions = Permission::all();
            // $existingPermissions = $role->permissions->pluck('id')->toArray();   

            // Fetch existing permissions for the role with application_id and permission_id
            $existingPermissions = \DB::table('role_permission')
                ->where('role_id', $id)
                ->select('application_id', 'permission_id')
                ->get()
                ->map(function ($item) {
                    return (array) $item;
                })
                ->toArray();
            // dd($permissions);
            return view('backend.role.edit', compact('selectedgroups', 'selectedusers', 'applications', 'users', 'groups', 'permissions', 'role', 'existingPermissions'));
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
            $role = Role::find($id);
            if ($role) {
                $data = $request->all();
                unset($data['_token']);
                unset($data['_method']);
                unset($data['user_list']);
                unset($data['group_list']);
                if ($request->user_list) {
                    # code...
                    $data['user_list'] = json_encode($request->user_list);
                }
                if ($request->group_list) {
                    # code...
                    $data['group_list'] = json_encode($request->group_list);
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
                $role->update($data);
                // $permissions = $request->input('permissions');
                // dd($permissions);

                // $syncData = [];

                // foreach ($permissions as $applicationId => $applicationPermissions) {
                //     foreach ($applicationPermissions as $permissionId => $permissionData) {
                //         $value = $permissionData['value'];
                //         preg_match('/\[(\d+)\]\[(\d+)\]/', $value, $matches);

                //         if (isset($matches[1]) && isset($matches[2])) {
                //             $extractedPermissionId = $matches[2];
                //             $syncData[$extractedPermissionId] = ['application_id' => $applicationId];
                //         }
                //     }
                // }

                // // Log the sync data for debugging
                // logger('Sync Data:', $syncData);

                // // Use sync to update the pivot table
                // $role->permissions()->sync($syncData);

                $syncData = [];

                // Get the permissions input from the request
                $permissions = $request->input('permissions', []);

                // Log the entire permissions array for debugging
                logger('Permissions Input:', ['permissions' => $permissions]);

                // Iterate over each application in the permissions input
                foreach ($permissions as $applicationId => $applicationPermissions) {
                    // Log each application ID for debugging
                    logger('Processing Application ID:', ['applicationId' => $applicationId]);

                    // Iterate over permissions for the current application
                    foreach ($applicationPermissions as $permissionId => $permissionData) {
                        $value = $permissionData['value'];
                        preg_match('/\[(\d+)\]\[(\d+)\]/', $value, $matches);

                        if (isset($matches[1]) && isset($matches[2])) {
                            $extractedPermissionId = $matches[2];
                            // Store both the permission ID and the application ID
                            $syncData[$extractedPermissionId] = ['application_id' => $applicationId];

                            // Log each permission being processed for debugging
                            logger('Processed Permission:', [
                                'extractedPermissionId' => $extractedPermissionId,
                                'applicationId' => $applicationId,
                            ]);
                        }
                    }
                }

                // Log the sync data for debugging
                logger('Sync Data:', ['syncData' => $syncData]);

                // Sync the permissions with the role
                $role->permissions()->sync($syncData);


                Log::channel('custom')->info('Userid -> ' . auth()->user()->custom_userid . ' , Role Edited by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' Current Data -> ' . 'o' . ' Changed Data -> ' . 'o');

                return redirect()->back()->with('success', 'Successfully Roles Updated.');
            } else {
                return redirect()->route('role.index')->with('error', 'not found');

            }
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
            $role = Role::find($id);
            if (!$role) {
                return redirect()->back()->with('success', 'Role not found');
            }
            $role->permissions()->detach();
            $role->delete();
            Log::channel('user')->info('Userid ' . auth()->user()->custom_userid . ' , Role Deleted by ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' Role Name -> ' . $role->name);
            // Role::destroy($id);
            return redirect()->back()->with('success', 'Successfully Deleted.');
            // dd($audit);
        } catch (\Exception $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}