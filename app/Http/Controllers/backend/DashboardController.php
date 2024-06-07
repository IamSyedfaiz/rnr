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
        $dashboardS = Dashboard::all();
        return view('backend.dashboard.index', compact('dashboardS'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $applications = Application::where('status', 1)->orderBy('name')->get();
        $reports = Report::all();
        $users = User::where('status', 1)
            ->latest()
            ->get();
        $groups = Group::where(['status' => 1])
            ->latest()
            ->get();
        $selectedgroups = [];

        $selectedusers = [];
        return view('backend.dashboard.add', compact('applications', 'reports', 'users', 'groups', 'selectedgroups', 'selectedusers'));
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
        $users = User::where('status', 1)
            ->latest()
            ->get();
        $groups = Group::where(['status' => 1])
            ->latest()
            ->get();

        $selectedgroups = [];

        $selectedusers = [];
        return view('backend.dashboard.edit', compact('dashboard', 'applications', 'reports', 'users', 'groups', 'selectedgroups', 'selectedusers'));
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