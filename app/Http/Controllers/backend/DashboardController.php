<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\backend\Application;
use App\Models\backend\Dashboard;
use App\Models\backend\Group;
use App\Models\backend\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->id == 1) {
            $dashboards = Dashboard::all();
        } else {

            $userId = auth()->user()->id;
            $userDashboards = Dashboard::whereJsonContains('user_list', (string) $userId)
                ->orWhere('user_id', $userId)
                ->get();

            // Retrieve group IDs where the authenticated user is listed
            $groupIds = Group::whereJsonContains('userids', (string) $userId)->pluck('id');

            // Initialize an empty collection for group dashboards
            $groupDashboards = collect();

            // Check if groupIds is not empty
            if ($groupIds->isNotEmpty()) {
                // Retrieve dashboards where any of the user's group IDs are listed
                $groupDashboards = Dashboard::where(function ($query) use ($groupIds) {
                    foreach ($groupIds as $groupId) {
                        $query->orWhereJsonContains('group_list', (string) $groupId);
                    }
                })->get();
            }

            // Combine the two collections and remove duplicates
            $dashboards = $userDashboards->merge($groupDashboards)->unique('id');
            // dd($dashboards);
        }
        return view('backend.dashboard.index', compact('dashboards'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (auth()->user()->id == 1) {
            $reports = Report::all();
        } else {
            $reports = Report::where('user_id', auth()->user()->id)
                ->orderBy('name')
                ->get();
        }

        $users = User::where('status', 1)->latest()->get();
        $groups = Group::where(['status' => 1])
            ->latest()
            ->get();
        $selectedgroups = [];

        $selectedusers = [];
        return view('backend.dashboard.add', compact('reports', 'users', 'groups', 'selectedgroups', 'selectedusers'));
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
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'alias' => 'nullable|string|max:255',
                'type' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:255',
                'layout' => 'nullable|string|max:255',
                'report_id' => 'nullable|array',
                'user_id' => 'nullable|integer',
                'access' => 'nullable|in:PR,PB',
                'active' => 'nullable|in:Y,N',
                'user_list' => 'nullable',
                'group_list' => 'nullable',
            ]);
            if (array_key_exists('report_id', $validatedData)) {
                // Check if 'report_id' is an array
                if (is_array($validatedData['report_id'])) {
                    $validatedData['report_id'] = implode(',', $validatedData['report_id']);
                } else {
                    // If 'report_id' is not an array, set it to null
                    $validatedData['report_id'] = null;
                }
            } else {
                // If 'report_id' key is not present, set it to null
                $validatedData['report_id'] = null;
            }
            if ($request->user_list) {
                # code...
                unset($validatedData['user_list']);
                $validatedData['user_list'] = json_encode($request->user_list);
            }

            if ($request->group_list) {
                # code...
                unset($validatedData['group_list']);
                $validatedData['group_list'] = json_encode($request->group_list);
            }
            // dd($validatedData);
            Dashboard::create($validatedData);

            Log::channel('custom')->info('Userid -> ' . auth()->user()->custom_userid . ' , Dashboard Created by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' Dashboard Name -> ' . $request->name);
            return redirect()->route('dashboard.index')->with('success', 'Dashboard saved successfully!.');
        } catch (\Exception $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\backend\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function show(Dashboard $dashboard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\backend\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function edit(Dashboard $dashboard)
    {
        $applications = Application::where('status', 1)->orderBy('name')->get();
        $reports = Report::all();
        $users = User::where('status', 1)->latest()->get();
        $groups = Group::where(['status' => 1])
            ->latest()
            ->get();

        $selectedgroups = [];
        if ($dashboard->group_list != 'null') {
            $groupids = json_decode($dashboard->group_list);
            # code...
            if ($groupids) {
                for ($i = 0; $i < count($groupids); $i++) {
                    # code...
                    $group = Group::find($groupids[$i]);
                    array_push($selectedgroups, $group);
                }
            }
        }

        $selectedusers = [];
        if ($dashboard->user_list != 'null') {
            $userids = json_decode($dashboard->user_list);
            # code...
            if ($userids) {
                for ($i = 0; $i < count($userids); $i++) {
                    # code...
                    $user = User::find($userids[$i]);
                    array_push($selectedusers, $user);
                }
            }
        }

        return view('backend.dashboard.edit', compact('dashboard', 'applications', 'reports', 'selectedusers', 'selectedgroups', 'users', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\backend\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dashboard $dashboard)
    {
        try {
            // dd($request->all());
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'alias' => 'nullable|string|max:255',
                'type' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:255',
                'layout' => 'nullable|string|max:255',
                'report_id' => 'nullable',
                'user_id' => 'nullable|integer',
                'access' => 'nullable|in:PR,PB',
                'active' => 'nullable|in:Y,N',
                'user_list' => 'nullable',
                'group_list' => 'nullable',
            ]);

            if (array_key_exists('report_id', $validatedData)) {
                // Check if 'report_id' is an array
                if (is_array($validatedData['report_id'])) {
                    $validatedData['report_id'] = implode(',', $validatedData['report_id']);
                } else {
                    // If 'report_id' is not an array, set it to null
                    $validatedData['report_id'] = $dashboard->report_id;
                }
            } else {
                // If 'report_id' key is not present, set it to null
                $validatedData['report_id'] = $dashboard->report_id;
            }

            $dashboard->update($validatedData);

            return redirect()->route('dashboard.index')->with('success', 'Dashboard updated successfully!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\backend\Dashboard  $dashboard
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dashboard $dashboard)
    {
        $dashboard->delete();

        return redirect()->back()->with('success', 'Dashboard deleted successfully!');
    }
    public function getReport(Request $request)
    {
        $reportIds = $request->input('reportIds');
        $reports = Report::whereIn('id', $reportIds)->get();

        return response()->json($reports);
    }
}