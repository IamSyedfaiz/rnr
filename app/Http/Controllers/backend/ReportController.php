<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\backend\Application;
use App\Models\backend\Field;
use App\Models\backend\Formdata;
use App\Models\backend\Group;
use App\Models\backend\Report;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function getView()
    {
        try {
            $reports = Report::all();

            return view('backend.reports.index', compact('reports'));
        } catch (\Exception $th) {
            //throw $th;
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
        }
    }
    public function getReportApplication()
    {
        try {
            $applications = Application::latest()->get();
            return view('backend.reports.getApplication', compact('applications'));
        } catch (\Exception $th) {
            //throw $th;
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
        }
    }
    public function sendReportApplication($id)
    {
        try {
            $report = Report::create([
                'application_id' => $id,
                'user_id' => auth()->user()->id,
            ]);
            $application = Application::find($id);
            $fields = Field::where('application_id', $id)
                ->where('status', 1)
                ->orderBy('name')
                ->get();
            return view('backend.reports.applicationFields', compact('application', 'fields', 'report'));
        } catch (\Exception $th) {
            //throw $th;
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
        }
    }

    public function searchReport(Request $request)
    {
        try {
            // dd($request->all());
            $reportId = $request->input('report_id');
            $existingReport = Report::find($reportId);
            $applicationId = $request->input('application_id');
            $statisticsMode = $request->input('statistics_mode', false);
            $dropdowns = $request->input('dropdowns', []);
            $fieldNames = $request->input('fieldNames', []);
            $fieldStatisticsNames = $request->input('fieldStatisticsNames', []);
            $fieldIds = $request->input('fieldIds', []);

            $formData = Formdata::where('application_id', $applicationId)
                ->pluck('data')
                ->toArray();
            $allData = [];

            foreach ($fieldStatisticsNames as $fieldName) {
                $fieldValues = [];

                foreach ($formData as $item) {
                    $data = json_decode($item, true);
                    $fieldValues[] = $data[$fieldName] ?? null;
                }
                $allData[$fieldName] = $fieldValues;
            }
            // dd($allData);

            $countData = [];
            $groupData = [];

            foreach ($dropdowns as $key => $dropdown) {
                $fieldName = $fieldNames[$key];
                $fieldId = $fieldIds[$key];
                $groupedData = collect($formData)->groupBy(function ($item) use ($fieldName) {
                    $data = json_decode($item, true); // Decode JSON string to array
                    return $data[$fieldName];
                });

                foreach ($groupedData as $fieldName => $items) {
                    // Count occurrences of the fieldName
                    $countData[$fieldName] = count($items);

                    // Group items by the fieldName
                    $groupData[$fieldName] = $items;
                }
            }

            // dd($existingReport);
            // dd($countData);
            if ($statisticsMode) {
                if ($existingReport) {
                    $existingReport->data = json_encode($countData);
                    $existingReport->dropdowns = json_encode($dropdowns);
                    $existingReport->fieldNames = json_encode($fieldNames);
                    $existingReport->fieldStatisticsNames = json_encode($fieldStatisticsNames);
                    $existingReport->fieldIds = json_encode($fieldIds);
                    $existingReport->statistics_mode = $statisticsMode ? 'Y' : 'N';
                    $existingReport->save();
                }
                return view('backend.reports.viewCart', compact('countData', 'applicationId', 'reportId'));
            } else {
                if ($existingReport) {
                    $existingReport->data = json_encode($allData);
                    $existingReport->dropdowns = json_encode($dropdowns);
                    $existingReport->fieldNames = json_encode($fieldNames);
                    $existingReport->fieldStatisticsNames = json_encode($fieldStatisticsNames);
                    $existingReport->fieldIds = json_encode($fieldIds);
                    $existingReport->statistics_mode = $statisticsMode ? 'Y' : 'N';
                    $existingReport->save();
                }
                return view('backend.reports.viewTable', compact('countData', 'allData', 'fieldStatisticsNames', 'applicationId', 'reportId'));
            }
        } catch (\Exception $th) {
            //throw $th;
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
        }
    }
    public function viewSaveReport($id)
    {
        try {
            $users = User::where('status', 1)
                ->latest()
                ->get();
            $groups = Group::where(['status' => 1])
                ->latest()
                ->get();

            $selectedgroups = [];

            $selectedusers = [];
            return view('backend.reports.saveReport', compact('users', 'groups', 'selectedgroups', 'selectedusers', 'id'));
        } catch (\Exception $th) {
            //throw $th;
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
        }
    }
    public function storeReport(Request $request)
    {
        try {

            $id = $request->input('report_id');
            $name = $request->input('name');
            $userList = $request->input('user_list');
            $groupList = $request->input('group_list');
            $groupList = $request->input('group_list');
            $description = $request->input('description');
            $permissions = $request->input('flexRadioDefault', null);

            $report = Report::find($id);

            if ($report) {
                // The report was found, so it's safe to update its properties
                $report->name = $name;
                $report->user_list = json_encode($userList);
                $report->group_list = json_encode($groupList);
                $report->permissions = $permissions;
                $report->description = $description;
                $report->save();

                return redirect()->route('get.view');
            } else {
                // Handle the case where the report with the specified ID was not found
                return redirect()->route('get.view')->with('error', 'Report not found.');
            }
        } catch (\Exception $th) {
            //throw $th;
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
        }
    }
    public function storeCertReport(Request $request)
    {
        try {
            $id = $request->input('report_id');
            $dataType = $request->input('data_type');
            $chartType = $request->input('chart_type');
            $report = Report::find($id);

            if ($report) {
                $report->data_type = $dataType;
                $report->chart_type = $chartType;
                $report->user_id = auth()->user()->id;
                $report->save();
                // dd($report);
                $users = User::where('status', 1)
                    ->latest()
                    ->get();
                $groups = Group::where(['status' => 1])
                    ->latest()
                    ->get();

                $selectedgroups = [];

                $selectedusers = [];
                return view('backend.reports.saveReport', compact('users', 'groups', 'selectedgroups', 'selectedusers', 'id'));
            } else {
                return redirect()->route('get.view')->with('error', 'Report not found.');
            }
        } catch (\Exception $th) {
            //throw $th;
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
        }
    }
    public function deleteReport($id)
    {
        try {
            $report = Report::findOrFail($id);

            // Perform the deletion
            $report->delete();

            return redirect()
                ->back()
                ->with('success', 'Report deleted successfully');
        } catch (\Exception $th) {
            //throw $th;
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
        }
    }
    public function viewChart($id)
    {
        try {
            $report = Report::findOrFail($id);
            $allData = json_decode($report->data, true);
            $countData = json_decode($report->data, true);
            $fieldStatisticsNames = json_decode($report->fieldStatisticsNames, true);
            // dd($countData);
            // dd($allData);

            if ($report->statistics_mode == 'Y') {
                return view('backend.reports.viewChartData', compact('countData', 'fieldStatisticsNames'));
            } else {
                return view('backend.reports.viewTableData', compact('allData', 'fieldStatisticsNames'));
            }
        } catch (\Exception $th) {
            //throw $th;
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
        }
    }
    public function editChart($id)
    {
        try {
            $report = Report::findOrFail($id);
            $users = User::where('status', 1)
                ->latest()
                ->get();
            $groups = Group::where(['status' => 1])
                ->latest()
                ->get();

                $selectedgroups = [];
                if ($report->group_list != 'null') {
                    $groupids = json_decode($report->group_list);
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
                if ($report->user_list != 'null') {
                    $userids = json_decode($report->user_list);
                    # code...
                    if ($userids) {
    
                        for ($i = 0; $i < count($userids); $i++) {
                            # code...
                            $user = User::find($userids[$i]);
                            array_push($selectedusers, $user);
                        }
                    }
                }
    
            return view('backend.reports.saveReport', compact('users', 'groups', 'selectedgroups', 'selectedusers', 'report', 'id'));
        } catch (\Exception $th) {
            //throw $th;
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
        }
    }
}