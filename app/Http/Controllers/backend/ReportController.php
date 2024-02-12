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
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function getView()
    {
        try {
            $reports = Report::all();
            $applications = Application::where('status', 1)
                ->orderBy('name')
                ->get();;

            return view('backend.reports.index', compact('reports', 'applications'));
        } catch (\Exception $th) {
            //throw $th;
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
        }
    }
    public function sendReportApplication(Request $request)
    {
        try {
            $request->validate([
                'selectedApplication' => 'required|exists:applications,id',
            ], [
                'selectedApplication.required' => 'Please select an application.',
                'selectedApplication.exists' => 'The selected application does not exist.',
            ]);
            $id = $request->input('selectedApplication');

            $application = Application::find($id);
            $fields = Field::where('application_id', $id)
                ->where('status', 1)
                ->orderBy('name')
                ->get();
            return view('backend.reports.applicationFields', compact('application', 'fields'));
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
            $applicationId = $request->input('application_id');
            $statisticsMode = $request->input('statistics_mode', false);
            $dropdowns = $request->input('dropdowns', []);
            $fieldNames = $request->input('fieldNames', []);
            $fieldStatisticsNames = $request->input('fieldStatisticsNames', []);
            $fieldIds = $request->input('fieldIds', []);

            if ($statisticsMode) {
                if (count($fieldNames) > 0) {
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

                    $countData = [];
                    $groupData = [];

                    foreach ($dropdowns as $key => $dropdown) {
                        $fieldName = $fieldNames[$key];
                        $fieldId = $fieldIds[$key];
                        $groupedData = collect($formData)->groupBy(function ($item) use ($fieldName) {
                            $data = json_decode($item, true);
                            return $data[$fieldName];
                        });

                        foreach ($groupedData as $fieldName => $items) {
                            $countData[$fieldName] = count($items);
                            $groupData[$fieldName] = $items;
                        }
                    }
                    if ($statisticsMode) {
                        return view('backend.reports.viewCart', compact('countData', 'applicationId', 'statisticsMode', 'fieldStatisticsNames', 'fieldNames', 'fieldIds', 'dropdowns'));
                    } else {
                        return view('backend.reports.viewTable', compact('countData', 'allData', 'fieldStatisticsNames', 'applicationId', 'statisticsMode', 'fieldStatisticsNames', 'fieldNames', 'fieldIds', 'dropdowns'));
                    }
                } else {
                    return redirect()->back()->with('error', 'Field are empty.');
                }
            } else {
                if (count($fieldStatisticsNames) > 0) {
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

                    $countData = [];
                    $groupData = [];

                    foreach ($dropdowns as $key => $dropdown) {
                        $fieldName = $fieldNames[$key];
                        $fieldId = $fieldIds[$key];
                        $groupedData = collect($formData)->groupBy(function ($item) use ($fieldName) {
                            $data = json_decode($item, true);
                            return $data[$fieldName];
                        });

                        foreach ($groupedData as $fieldName => $items) {
                            $countData[$fieldName] = count($items);
                            $groupData[$fieldName] = $items;
                        }
                    }
                    if ($statisticsMode) {
                        return view('backend.reports.viewCart', compact('countData', 'applicationId', 'statisticsMode', 'fieldStatisticsNames', 'fieldNames', 'fieldIds', 'dropdowns'));
                    } else {
                        return view('backend.reports.viewTable', compact('countData', 'allData', 'fieldStatisticsNames', 'applicationId', 'statisticsMode', 'fieldStatisticsNames', 'fieldNames', 'fieldIds', 'dropdowns'));
                    }
                } else {
                    return redirect()->back()->with('error', 'Field are empty.');
                }
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
            dd($request->all());
            $statisticsMode = $request->input('statistics_mode', false);
            $name = $request->input('name');
            $data = $request->input('data');
            $radioDefault = $request->input('radioDefault');
            $userList = $request->input('user_list');
            $groupList = $request->input('group_list');
            $groupList = $request->input('group_list');
            $description = $request->input('description');
            $radioDefault = $request->input('radioDefault');
            $permissions = $request->input('flexRadioDefault', null);
            if ($statisticsMode) {
                
            } else {
            }


            return redirect()->route('get.view');
        } catch (\Exception $th) {
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
        }
    }
    public function storeCertReport(Request $request)
    {
        try {
            // dd($request->all());
            $applicationId = $request->input('application_id');
            $data = $request->input('data');
            $dropdowns = $request->input('dropdowns');
            $fieldIds = $request->input('fieldIds');
            $fieldNames = $request->input('fieldNames');
            $statisticsMode = $request->input('statisticsMode');
            $fieldStatisticsNames = $request->input('fieldStatisticsNames');

            $users = User::where('status', 1)
                ->latest()
                ->get();
            $groups = Group::where(['status' => 1])
                ->latest()
                ->get();

            $selectedgroups = [];

            $selectedusers = [];
            return view('backend.reports.saveReport', compact('users', 'groups', 'selectedgroups', 'selectedusers', 'applicationId', 'data', 'statisticsMode', 'fieldStatisticsNames', 'fieldNames', 'dropdowns'));
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