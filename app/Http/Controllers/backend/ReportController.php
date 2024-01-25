<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\backend\Application;
use App\Models\backend\Field;
use App\Models\backend\Formdata;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function getView()
    {
        try {
            return view('backend.reports.index');
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
            $application = Application::find($id);
            $fields = Field::where('application_id', $id)->where('status', 1)->orderBy('name')->get();
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
            $statisticsMode = $request->input('statistics_mode', false);
            $applicationId = $request->input('application_id');
            $dropdowns = $request->input('dropdowns', []);
            $fieldNames = $request->input('fieldNames', []);
            $fieldStatisticsNames = $request->input('fieldStatisticsNames', []);
            $fieldIds = $request->input('fieldIds', []);

            $formData = Formdata::where('application_id', $applicationId)->pluck('data')->toArray();
            $allData = [];

            foreach ($fieldStatisticsNames as $fieldName) {
                $fieldValues = [];

                foreach ($formData as $item) {
                    $data = json_decode($item, true); // Decode JSON string to array
                    $fieldValues[] = $data[$fieldName] ?? null;
                }

                // Store the field values in $allData
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
            // dd($groupData);
            if ($statisticsMode) {
                return view('backend.reports.viewCart', compact('countData'));
            } else {
                return view('backend.reports.viewTable', compact('countData', 'allData', 'fieldStatisticsNames'));
            }
        } catch (\Exception $th) {
            //throw $th;
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
        }
    }
    public function viewSaveReport()
    {
        try {
            return view('backend.reports.saveReport');
        } catch (\Exception $th) {
            //throw $th;
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
        }
    }
    public function storeReport()
    {
        dd(1);
        try {
            return view('backend.reports.saveReport');
        } catch (\Exception $th) {
            //throw $th;
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
        }
    }
}
