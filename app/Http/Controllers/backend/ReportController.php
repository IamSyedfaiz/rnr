<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\backend\Application;
use App\Models\backend\Field;
use App\Models\backend\Formdata;
use App\Models\backend\Group;
use App\Models\backend\Report;
use App\Models\backend\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class ReportController extends Controller
{
    public function getView()
    {
        try {
            if (auth()->user()->id == 1) {
                $reports = Report::all();
                $applications = Application::where('status', 1)->orderBy('name')->get();
            } else {
                $reports = Report::where('user_id', auth()->user()->id)
                    ->orderBy('name')
                    ->get();
                // $applications = Application::where('status', 1)->orderBy('name')->get()
                $user = auth()->user();
                $userId = $user->id;
                $directRoles = Role::whereJsonContains('user_list', (string) $userId)->with('permissions.applications')->get();

                $groupIds = Group::whereJsonContains('userids', (string) $userId)->pluck('id')->toArray();

                $groupRoles = Role::where(function ($query) use ($groupIds) {
                    foreach ($groupIds as $groupId) {
                        $query->orWhereJsonContains('group_list', (string) $groupId);
                    }
                })
                    ->with('permissions.applications')
                    ->get();

                $allRoles = $directRoles->merge($groupRoles);

                $applications = [];
                foreach ($allRoles as $permission) {
                    foreach ($permission->applications as $application) {
                        if (!isset($applications[$application->id])) {
                            $applications[$application->id] = $application;
                        }
                    }
                }
            }

            return view('backend.reports.index', compact('reports', 'applications'));
        } catch (\Exception $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function sendReportApplication(Request $request)
    {
        try {
            $request->validate(
                [
                    'selectedApplication' => 'required|exists:applications,id',
                ],
                [
                    'selectedApplication.required' => 'Please select an application.',
                    'selectedApplication.exists' => 'The selected application does not exist.',
                ],
            );
            $id = $request->input('selectedApplication');
            $currentApplicationId = Session::get('applicationId');

            // Check if the new application ID is different from the current one
            if ($id != $currentApplicationId) {
                // Clear the entire session if a new application ID is received
                // Session::flush();
                Session::forget(['applicationId', 'statisticsMode', 'dropdowns', 'fieldNames', 'fieldStatisticsNames', 'dropdownFieldIds', 'filterOperators', 'filterValues', 'advancedOperatorLogic', 'fieldIds']);
            }
            $application = Application::find($id);
            $fields = Field::where('application_id', $id)->where('status', 1)->orderBy('name')->get();
            return view('backend.reports.applicationFields', compact('application', 'fields'));
        } catch (\Exception $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function backReportApplication($id)
    {
        try {
            $application = Application::find($id);
            $fields = Field::where('application_id', $id)->where('status', 1)->orderBy('name')->get();
            return view('backend.reports.applicationFields', compact('application', 'fields'));
        } catch (\Exception $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function extractVariablesAndOperators($inputString)
    {
        $tokens = [];
        $operators = ['AND', 'OR', '(', ')'];

        // Build a regular expression pattern for capturing tokens
        $pattern = '/\b(?:' . implode('|', array_map('preg_quote', $operators)) . ')\b|\d+|\(|\)/';

        // Use preg_match_all to capture all matching tokens
        preg_match_all($pattern, $inputString, $matches);

        // Flatten the matches array
        $matches = array_reduce($matches, 'array_merge', []);

        foreach ($matches as $match) {
            $tokens[] = ['type' => is_numeric($match) ? 'variable' : 'operator', 'value' => $match];
        }

        return $tokens;
    }
    public function rebuildString($tokens)
    {
        $result = '';

        foreach ($tokens as $token) {
            if ($token['type'] === 'operator' && ($token['value'] === '(' || $token['value'] === ')')) {
                $result .= $token['value'] . ' ';
            } else {
                $result .= $token['value'] . ' ';
            }
        }

        return trim(preg_replace('/\s+/', ' ', $result));
    }
    public function getVariables($tokens)
    {
        $variables = [];

        foreach ($tokens as $token) {
            if ($token['type'] === 'variable') {
                $variables[] = $token['value'];
            }
        }

        return $variables;
    }
    public function searchReport(Request $request)
    {
        try {
            // dd($request->all());
            $currentApplicationId = Session::get('applicationId');
            $applicationId = $request->input('application_id');
            $reportId = $request->input('report_id');

            // Check if the new application ID is different from the current one
            if ($applicationId != $currentApplicationId) {
                // Clear the entire session if a new application ID is received
                // Session::flush();
                Session::forget(['dataType', 'selectChart', 'borderWidth', 'legendPosition', 'labelColor']);
            }

            $statisticsMode = $request->input('statistics_mode', false);
            $dropdowns = $request->input('dropdowns', []);
            $fieldNames = $request->input('fieldNames', []);
            $fieldStatisticsNames = $request->input('fieldStatisticsNames', []);
            $dropdownFieldIds = $request->input('fieldIds', []);
            $filterOperators = $request->input('filter_operator', []);
            $filterValues = $request->input('filter_value', []);
            $advancedOperatorLogic = $request->input('advanced_operator_logic', []);
            $fieldIds = $request->input('field_id', []);
            Session::put('applicationId', $applicationId);
            Session::put('statisticsMode', $statisticsMode);
            Session::put('dropdowns', $dropdowns);
            Session::put('fieldNames', $fieldNames);
            Session::put('fieldStatisticsNames', $fieldStatisticsNames);
            Session::put('dropdownFieldIds', $dropdownFieldIds);
            Session::put('filterOperators', $filterOperators);
            Session::put('filterValues', $filterValues);
            Session::put('advancedOperatorLogic', $advancedOperatorLogic);
            Session::put('fieldIds', $fieldIds);
            // dd($fieldIds);

            if (in_array(null, $fieldIds)) {
                if ($statisticsMode) {
                    if (count($fieldNames) > 0) {
                        $formData = Formdata::where('application_id', $applicationId)->pluck('data')->toArray();

                        $countData = [];
                        $groupData = [];

                        foreach ($dropdowns as $key => $dropdown) {
                            $fieldName = $fieldNames[$key];
                            $groupedData = collect($formData)->groupBy(function ($item) use ($fieldName) {
                                $data = json_decode($item, true);
                                return $data[$fieldName];
                            });

                            foreach ($groupedData as $fieldName => $items) {
                                // $countData[$fieldName] = count($items);
                                // $groupData[$fieldName] = 1;
                                $groupedItems = $items->all();
                                if ($dropdown === 'group_by') {
                                    $countData[$fieldName] = 1;
                                } elseif ($dropdown === 'count_of') {
                                    $countData[$fieldName] = count($groupedItems);
                                }
                            }
                        }
                        // logger($countData);
                        // dd($countData);

                        return view('backend.reports.viewCart', compact('countData', 'groupData', 'applicationId', 'statisticsMode', 'fieldStatisticsNames', 'fieldNames', 'fieldIds', 'dropdowns', 'reportId'));
                    } else {
                        return redirect()->back()->with('error', 'Field are empty.');
                    }
                } else {
                    if (count($fieldStatisticsNames) > 0) {
                        $formData = Formdata::where('application_id', $applicationId)->pluck('data')->toArray();
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
                            $groupedData = collect($formData)->groupBy(function ($item) use ($fieldName) {
                                $data = json_decode($item, true);
                                return $data[$fieldName];
                            });

                            foreach ($groupedData as $fieldName => $items) {
                                $countData[$fieldName] = count($items);
                                $groupData[$fieldName] = $items;
                            }
                        }
                        return view('backend.reports.viewTable', compact('countData', 'allData', 'fieldStatisticsNames', 'applicationId', 'statisticsMode', 'fieldStatisticsNames', 'fieldNames', 'fieldIds', 'dropdowns', 'reportId'));
                    } else {
                        return redirect()->back()->with('error', 'Field are empty.');
                    }
                }
            } else {
                if ($statisticsMode) {
                    // dd('$fieldNames');
                    // dd($fieldNames);

                    if (count($fieldNames) > 0) {
                        $formData = Formdata::where('application_id', $applicationId)->pluck('data')->toArray();
                        $filterData = [];
                        $count = count($fieldIds);
                        for ($i = 0; $i < $count; $i++) {
                            $filterData[] = [
                                'field_id' => $fieldIds[$i],
                                'filter_operator' => $filterOperators[$i],
                                'filter_value' => $filterValues[$i],
                            ];
                        }

                        $final_rows = [];

                        foreach ($formData as $data) {
                            $data = json_decode($data, true);
                            $filteredData = [];
                            foreach ($filterData as $filter) {
                                $fieldId = $filter['field_id'];
                                $filterOperator = $filter['filter_operator'];
                                $filterValue = $filter['filter_value'];

                                if (is_array($data) && !empty($data)) {
                                    $fieldName = Field::find($fieldId)->name;
                                    switch ($filterOperator) {
                                        case 'C':
                                            if (strpos($data[$fieldName], $filterValue) !== false) {
                                                $filteredData[] = true;
                                                logger("Contains comparison: IDs match. Request value: {$data[$fieldName]}, filter value: {$filterValue}");
                                            } else {
                                                $filteredData[] = false;
                                                logger("Contains comparison: IDs match. Request value: {$data[$fieldName]}, filter value: {$filterValue}");
                                            }
                                            break;
                                        case 'DNC':
                                            if (strpos($data[$fieldName], $filterValue) === false) {
                                                $filteredData[] = true;
                                                logger("Does Not Contain comparison: IDs match. Request value: {$data[$fieldName]}, filter value: {$filterValue}");
                                            } else {
                                                $filteredData[] = false;
                                                logger("Does Not Contain comparison: IDs do not match. Request value: {$data[$fieldName]}, filter value: {$filterValue}");
                                            }
                                            break;
                                        case 'E':
                                            if ($data[$fieldName] === $filterValue) {
                                                $filteredData[] = true;
                                                logger("Contains comparison: IDs match. Request value: {$data[$fieldName]}, filter value: {$filterValue}");
                                            } else {
                                                $filteredData[] = false;
                                                logger("Contains comparison: IDs match. Request value: {$data[$fieldName]}, filter value: {$filterValue}");
                                            }
                                            break;
                                        case 'DNE':
                                            if ($data[$fieldName] !== $filterValue) {
                                                $filteredData[] = true;
                                                logger("Does Not Equals comparison: IDs match. Request value: {$data[$fieldName]}, filter value: {$filterValue}");
                                            } else {
                                                $filteredData[] = false;
                                                logger("Does Not Equals comparison: IDs do not match. Request value: {$data[$fieldName]}, filter value: {$filterValue}");
                                            }
                                            break;
                                    }
                                }
                            }

                            // dd($advancedOperatorLogic);
                            // dd('yahi hai');

                            if ($advancedOperatorLogic) {
                                $extractedTokens = $this->extractVariablesAndOperators($advancedOperatorLogic);
                                $variables = $this->getVariables($extractedTokens);
                                $reconstructedString = $this->rebuildString($extractedTokens);
                                foreach ($extractedTokens as &$token) {
                                    if ($token['type'] === 'variable') {
                                        $variableValue = intval($token['value']);
                                        if (isset($filteredData[$variableValue - 1])) {
                                            $token['value'] = $filteredData[$variableValue - 1] ? '1' : '0';
                                        }
                                    }
                                }

                                $reconstructedString = $this->rebuildString($extractedTokens);
                                $reconstructedString = str_replace('AND', '&&', $reconstructedString);
                                $reconstructedString = str_replace('OR', '||', $reconstructedString);
                                logger($reconstructedString);
                                logger(eval ("return $reconstructedString;"));

                                if (eval ("return $reconstructedString;")) {
                                    $final_rows[] = $data;
                                }
                            } else {
                                $arrayAsString = implode(
                                    ' && ',
                                    array_map(function ($value) {
                                        return $value ? 'true' : 'false';
                                    }, $filteredData),
                                );
                                if (eval ("return $arrayAsString;")) {
                                    $final_rows[] = $data;
                                }
                            }
                        }

                        // dd($final_rows);
                        // dd('yahi hai');
                        if (count($final_rows) > 0) {
                            $allData = [];
                            foreach ($final_rows as $row) {
                                foreach ($row as $key => $value) {
                                    if (!isset($allData[$key])) {
                                        $allData[$key] = [];
                                    }
                                    $allData[$key][] = $value;
                                }
                            }
                            $transformedData = [];

                            // Iterate over the data array and transform each element to JSON
                            foreach ($allData['id'] as $index => $id) {
                                $transformedData[] = json_encode([
                                    'id' => $id,
                                    'name' => $allData['name'][$index],
                                    'email' => $allData['email'][$index],
                                ]);
                            }
                            logger($transformedData);
                            logger($dropdowns);
                            // dd($allData);
                            logger($formData);
                            // dd($formData);

                            // foreach ($dropdowns as $key => $dropdown) {
                            //     $fieldName = $fieldNames[$key];
                            //     $groupedData = collect($allData)->groupBy(function ($item) use ($fieldName) {
                            //         return $item[$fieldName];
                            //     });
                            //     dd($groupedData);

                            //     $countData = [];
                            //     $groupData = [];
                            //     foreach ($groupedData as $fieldName => $items) {
                            //         // $countData[$fieldName] = count($items);
                            //         // $groupData[$fieldName] = 1;
                            //         $groupedItems = $items->all();
                            //         if ($dropdown === 'group_by') {
                            //             $countData[$fieldName] = 1;
                            //         } elseif ($dropdown === 'count_of') {
                            //             $countData[$fieldName] = count($groupedItems);
                            //         }
                            //     }
                            // }
                            $countData = [];
                            $groupData = [];
                            foreach ($dropdowns as $key => $dropdown) {
                                $fieldName = $fieldNames[$key];
                                // dd($allData);
                                // Group the data by the current field
                                $transformedGroup = collect($transformedData)->groupBy(function ($item) use ($fieldName) {
                                    $data = json_decode($item, true);

                                    return $data[$fieldName];
                                });
                                logger($transformedGroup);
                                foreach ($transformedGroup as $fieldName => $items) {
                                    // Get the items as an array
                                    $groupedItems = $items->all();

                                    // Calculate the count based on the dropdown value
                                    if ($dropdown === 'group_by') {
                                        // For 'group_by', set count to 1 for each group
                                        $countData[$fieldName] = 1;
                                    } elseif ($dropdown === 'count_of') {
                                        // For 'count_of', count the number of items in each group
                                        $countData[$fieldName] = count($groupedItems);
                                    }
                                }

                                // Debug the countData for the current field
                            }
                            logger($countData);
                            // dd($countData);

                            // dd($allData);
                            return view('backend.reports.viewCart', compact('countData', 'groupData', 'applicationId', 'statisticsMode', 'fieldStatisticsNames', 'fieldNames', 'fieldIds', 'dropdowns', 'reportId'));
                        } else {
                            return redirect()->back()->with('error', 'Advanced Logic Not Valid.');
                        }

                        // logger('dropdowns');
                        // logger($dropdowns);
                        // logger('fieldNames');
                        // logger($fieldNames);
                        // logger('dropdownFieldIds');
                        // logger($dropdownFieldIds);
                        // logger('formData');
                        // logger($formData);
                    } else {
                        return redirect()->back()->with('error', 'Field are empty.');
                    }
                } else {
                    if (count($fieldStatisticsNames) > 0) {
                        $filterData = [];
                        $count = count($fieldIds);
                        for ($i = 0; $i < $count; $i++) {
                            $filterData[] = [
                                'field_id' => $fieldIds[$i],
                                'filter_operator' => $filterOperators[$i],
                                'filter_value' => $filterValues[$i],
                            ];
                        }

                        $formData = Formdata::where('application_id', $applicationId)->pluck('data')->toArray();

                        $final_rows = [];

                        foreach ($formData as $data) {
                            $data = json_decode($data, true);
                            $filteredData = [];
                            $filteredDataNor = [];
                            foreach ($filterData as $filter) {
                                $fieldId = $filter['field_id'];
                                $filterOperator = $filter['filter_operator'];
                                $filterValue = $filter['filter_value'];

                                if (is_array($data) && !empty($data)) {
                                    $fieldName = Field::find($fieldId)->name;
                                    switch ($filterOperator) {
                                        case 'C':
                                            if (strpos($data[$fieldName], $filterValue) !== false) {
                                                $filteredData[] = true;
                                                logger("Contains comparison: IDs match. Request value: {$data[$fieldName]}, filter value: {$filterValue}");
                                            } else {
                                                $filteredData[] = false;

                                                logger("Contains comparison: IDs match. Request value: {$data[$fieldName]}, filter value: {$filterValue}");
                                            }
                                            break;
                                        case 'DNC':
                                            if (strpos($data[$fieldName], $filterValue) === false) {
                                                $filteredData[] = true;

                                                logger("Does Not Contain comparison: IDs match. Request value: {$data[$fieldName]}, filter value: {$filterValue}");
                                            } else {
                                                $filteredData[] = false;

                                                logger("Does Not Contain comparison: IDs do not match. Request value: {$data[$fieldName]}, filter value: {$filterValue}");
                                            }
                                            break;
                                        case 'E':
                                            if ($data[$fieldName] === $filterValue) {
                                                $filteredData[] = true;

                                                logger("Contains comparison: IDs match. Request value: {$data[$fieldName]}, filter value: {$filterValue}");
                                            } else {
                                                $filteredData[] = false;

                                                logger("Contains comparison: IDs match. Request value: {$data[$fieldName]}, filter value: {$filterValue}");
                                            }
                                            break;
                                        case 'DNE':
                                            if ($data[$fieldName] !== $filterValue) {
                                                $filteredData[] = true;

                                                logger("Does Not Equals comparison: IDs match. Request value: {$data[$fieldName]}, filter value: {$filterValue}");
                                            } else {
                                                $filteredData[] = false;

                                                logger("Does Not Equals comparison: IDs do not match. Request value: {$data[$fieldName]}, filter value: {$filterValue}");
                                            }
                                            break;
                                    }
                                }
                            }
                            if ($advancedOperatorLogic) {
                                $extractedTokens = $this->extractVariablesAndOperators($advancedOperatorLogic);
                                $variables = $this->getVariables($extractedTokens);
                                $reconstructedString = $this->rebuildString($extractedTokens);
                                foreach ($extractedTokens as &$token) {
                                    if ($token['type'] === 'variable') {
                                        $variableValue = intval($token['value']);
                                        if (isset($filteredData[$variableValue - 1])) {
                                            $token['value'] = $filteredData[$variableValue - 1] ? '1' : '0';
                                        }
                                    }
                                }

                                $reconstructedString = $this->rebuildString($extractedTokens);
                                $reconstructedString = str_replace('AND', '&&', $reconstructedString);
                                $reconstructedString = str_replace('OR', '||', $reconstructedString);
                                logger($reconstructedString);
                                logger(eval ("return $reconstructedString;"));

                                if (eval ("return $reconstructedString;")) {
                                    $final_rows[] = $data;
                                }
                            } else {
                                $arrayAsString = implode(
                                    ' && ',
                                    array_map(function ($value) {
                                        return $value ? 'true' : 'false';
                                    }, $filteredData),
                                );
                                if (eval ("return $arrayAsString;")) {
                                    $final_rows[] = $data;
                                }
                            }
                        }
                        if (count($final_rows) > 0) {
                            $allData = [];
                            foreach ($final_rows as $row) {
                                foreach ($row as $key => $value) {
                                    if (!isset($allData[$key])) {
                                        $allData[$key] = [];
                                    }
                                    $allData[$key][] = $value;
                                }
                            }

                            return view('backend.reports.viewTable', compact('allData', 'fieldStatisticsNames', 'applicationId', 'statisticsMode', 'fieldStatisticsNames', 'fieldNames', 'fieldIds', 'dropdowns', 'reportId'));
                        } else {
                            return redirect()->back()->with('error', 'Advanced Logic Not Valid.');
                        }
                    } else {
                        return redirect()->back()->with('error', 'Field are empty.');
                    }
                }
            }
        } catch (\Exception $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function viewSaveReport($id)
    {
        try {
            $users = User::where('status', 1)->latest()->get();
            $groups = Group::where(['status' => 1])
                ->latest()
                ->get();

            $selectedgroups = [];

            $selectedusers = [];
            return view('backend.reports.saveReport', compact('users', 'groups', 'selectedgroups', 'selectedusers', 'id'));
        } catch (\Exception $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function storeReport(Request $request)
    {
        try {
            // dd($request->all());
            $rules = [
                'application_id' => 'required|numeric',
                'user_id' => 'required|numeric',
                'name' => 'required|string',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            $reportId = $request->input('report_id');

            if ($reportId) {
                $report = Report::findOrFail($reportId);
                $message = 'Report updated successfully';
            } else {
                $report = new Report();
                $message = 'Report Add successfully';
            }
            $report->statistics_mode = $request->input('statisticsMode') ? 'Y' : 'N';
            $report->application_id = $request->input('application_id');
            $report->user_id = $request->input('user_id');
            $report->name = $request->input('name');
            $report->data = $request->input('data');
            $report->labelColor = $request->input('labelColor');
            $report->radioDefault = $request->input('radioDefault');
            $report->fieldStatisticsNames = $request->input('fieldStatisticsNames');
            $report->user_list = json_encode($request->input('user_list'));
            $report->group_list = json_encode($request->input('group_list'));
            $report->description = $request->input('description');
            $report->data_type = $request->input('dataType');
            $report->chart_type = $request->input('chart_type');
            $report->selectChart = $request->input('selectChart');
            $report->borderWidth = $request->input('borderWidth');
            $report->legendPosition = $request->input('legendPosition');
            $report->selectedPalette = $request->input('selectedPalette');
            $report->fieldNames = $request->input('fieldNames');
            $report->dropdowns = $request->input('dropdowns');
            $report->permissions = $request->input('flexRadioDefault', null);
            $report->save();

            return redirect()->route('get.view')->with('success', $message);
        } catch (\Exception $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function storeCertReport(Request $request)
    {
        try {
            // dd($request->all());
            $applicationId = $request->input('application_id');
            $report_id = $request->input('report_id');
            $data = $request->input('data');
            $dropdowns = $request->input('dropdowns');
            $fieldIds = $request->input('fieldIds');
            $fieldNames = $request->input('fieldNames');
            $statisticsMode = $request->input('statisticsMode');
            $dataType = $request->input('data_type');
            $fieldStatisticsNames = $request->input('fieldStatisticsNames');
            $selectChart = $request->input('selectChart');
            $borderWidth = $request->input('borderWidth');
            $legendPosition = $request->input('legendPosition');
            $selectedPalette = $request->input('selectedPalette');
            $labelColor = json_encode($request->input('labelColor'));
            if ($report_id) {
                $report = Report::findOrFail($report_id);
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
            } else {
                $report = null;
                $selectedgroups = [];
                $selectedusers = [];
            }

            Session::put('dataType', $dataType);
            Session::put('selectChart', $selectChart);
            Session::put('borderWidth', $borderWidth);
            Session::put('legendPosition', $legendPosition);
            Session::put('labelColor', $labelColor);
            Session::put('selectedPalette', $selectedPalette);
            $users = User::where('status', 1)->latest()->get();
            $groups = Group::where(['status' => 1])
                ->latest()
                ->get();

            return view('backend.reports.saveReport', compact('users', 'groups', 'selectedgroups', 'selectedusers', 'applicationId', 'data', 'statisticsMode', 'fieldStatisticsNames', 'fieldNames', 'dropdowns', 'dataType', 'selectChart', 'borderWidth', 'labelColor', 'legendPosition', 'report_id', 'report', 'selectedPalette'));
        } catch (\Exception $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function deleteReport($id)
    {
        try {
            $report = Report::findOrFail($id);

            // Perform the deletion
            $report->delete();

            return redirect()->back()->with('success', 'Report deleted successfully');
        } catch (\Exception $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function viewChart($id)
    {
        try {
            $report = Report::findOrFail($id);
            $allData = json_decode($report->data, true);
            $countData = json_decode($report->data, true);
            $fieldStatisticsNames = json_decode($report->fieldStatisticsNames, true);
            // dd($report->statistics_mode);
            // dd($allData);

            if ($report->statistics_mode == 'Y') {
                return view('backend.reports.viewChartData', compact('countData', 'fieldStatisticsNames'));
            } else {
                // dd($fieldStatisticsNames);
                return view('backend.reports.viewTableData', compact('allData', 'fieldStatisticsNames'));
            }
        } catch (\Exception $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function editChart($id)
    {
        try {
            $report = Report::findOrFail($id);
            $applicationId = $report->application_id;
            $application = Application::find($applicationId);
            $fields = Field::where('application_id', $applicationId)->where('status', 1)->orderBy('name')->get();

            return view('backend.reports.editApplicationFields', compact('application', 'fields', 'id'));
        } catch (\Exception $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function handleFilteredDataRequest(Request $request)
    {
        $filteredData = json_decode($request->input('filteredData'), true);

        // Pass the filtered data to the Blade view
        return view('backend.reports.filteredDataView')->with('filteredData', $filteredData);
    }
    public function removeFromSession($fieldNameToRemove)
    {
        $fieldNames = session('fieldNames');
        if (is_array($fieldNames)) {
            if (($key = array_search($fieldNameToRemove, $fieldNames)) !== false) {
                unset($fieldNames[$key]);
                session(['fieldNames' => $fieldNames]);
            }
        }
        return redirect()->back();
    }
    public function removeFromSessionNormal($fieldNameToRemove)
    {
        $fieldNames = session('fieldStatisticsNames');
        // dd($fieldNameToRemove);
        if (is_array($fieldNames)) {
            if (($key = array_search($fieldNameToRemove, $fieldNames)) !== false) {
                unset($fieldNames[$key]);
                session(['fieldStatisticsNames' => $fieldNames]);
            }
        }
        return redirect()->back();
    }
}