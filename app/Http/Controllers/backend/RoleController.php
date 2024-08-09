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
            // Validation rules
            $rules = [
                'user_list' => 'nullable|array',
                'group_list' => 'nullable|array',
                'permission' => 'required|array',
                'user_id' => 'required|integer',
                'name' => 'required|string',
                'description' => 'nullable|string',
                'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png,xlsx,xls,csv,docx|max:2048',
            ];

            // Custom validation messages
            $messages = [
                'description.required' => 'The description field is required.',
                'description.string' => 'The description must be a valid string.',
                'attachment.file' => 'The attachment must be a file.',
                'attachment.mimes' => 'The attachment must be a file of type: jpg, png, pdf, docx.',
                'attachment.max' => 'The attachment must not be greater than 2MB.',
                'permission.required' => 'Please select permissions',
                'user_id.required' => 'The user ID is required',
                'user_id.integer' => 'The user ID must be an integer',
            ];

            // Validate the request
            $validatedData = $request->validate($rules, $messages);
            $data = $request->all();

            // Handle file upload
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $originalName = $file->getClientOriginalName();
                $uniqueName = time() . '_' . $originalName;
                $size = round($file->getSize() / 1024, 4) . 'KB';
                $type = $file->getMimeType();
                $destinationPath = public_path('/role');

                // Check if directory exists, if not, create it
                if (!is_dir($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                // Move the file to the destination path and check if successful
                if ($file->move($destinationPath, $uniqueName)) {
                    // Add file details to the data array
                    $data['attachment_name'] = $originalName;
                    $data['attachment'] = $uniqueName;
                    $data['attachment_size'] = $size;
                    $data['attachment_type'] = $type;
                } else {
                    return redirect()->back()->with('error', 'Failed to save the file. Please try again.');
                }
            }
            // dd($data);
            unset($data['_token']);
            unset($data['user_list']);
            unset($data['group_list']);
            $data['user_list'] = json_encode($request->user_list);
            $data['group_list'] = json_encode($request->group_list);
            $data['user_id'] = $request->input('user_id');
            $permissions = $request->input('permission');

            $role1 = Role::create($data);
            if ($permissions) {
                $permissionsToInsert = [];
                if ($role1) {
                    foreach ($permissions as $key => $permission) {
                        // dd($permission, $key);

                        $permissionIds = array_map('intval', $permission);
                        $role1->permissions()->attach($permissionIds, ['application_id' => $key]);
                    }
                } else {
                    return redirect()->back()->with('error', 'Role creation failed');
                }
            } else {
                return redirect()->back()->with('error', 'Please select permissions');
            }

            Log::channel('custom')->info('Userid -> ' . auth()->user()->custom_userid . ' , Role Created by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname);

            return redirect()->route('role.index')->with('success', 'Successfully Roles Created.');
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

                // dd($data);
                if ($request->hasFile('attachment')) {
                    $file = $request->file('attachment');
                    $originalName = $file->getClientOriginalName();
                    $uniqueName = time() . '_' . $originalName;
                    $size = round($file->getSize() / 1024, 4) . 'KB';
                    $type = $file->getMimeType();
                    $destinationPath = public_path('/role');
                    // Check if the directory exists, if not, create it
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }

                    // Move the file to the destination path and check if successful
                    if ($file->move($destinationPath, $uniqueName)) {
                        // Delete the old file if it exists
                        if (!empty($role->attachment)) {
                            $oldFilePath = $destinationPath . '/' . $role->attachment;
                            if (file_exists($oldFilePath)) {
                                unlink($oldFilePath);
                            }
                        }

                        $data['attachment'] = $uniqueName;
                    } else {
                        return redirect()->back()->with('error', 'Failed to save the new file. Please try again.');
                    }
                }

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

                $permissions = $request->input('permissions', []);
                if ($permissions) {
                    $role->permissions()->detach();
                    foreach ($permissions as $key => $permission) {
                        $role->permissions()->attach($permission, ['application_id' => $key]);
                    }
                }

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
