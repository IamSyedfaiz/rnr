<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\backend\Role;
use App\Models\backend\Group;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Models\backend\Application;

class MultipleroleController extends Controller
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
        try {
            //code...
            $application = Application::find($id);
            // dd($id);
            $roles = Role::where('application_id', $id)
                ->latest()
                ->get();
            // dd($applications, $roles);
            return view('backend.multiplerole.index', compact('application', 'roles'));
        } catch (\Exception $th) {
            //throw $th;
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
        }
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
            $role = Role::where('application_id', $id)->first();
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
            return view('backend.multiplerole.edit', compact('selectedgroups', 'selectedusers', 'application', 'applicationrole', 'users', 'groups'));
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
        //
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
    }
}
