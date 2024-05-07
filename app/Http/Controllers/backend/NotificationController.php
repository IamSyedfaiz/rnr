<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\backend\Notification;
use App\Models\backend\Application;
use App\Models\backend\Field;
use App\Models\backend\FilterCriteria;
use App\Models\backend\Group;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class NotificationController extends Controller
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
            $notifications = Notification::latest()->get();
            // dd($notifications);
            return view('backend.notifications.index', compact('notifications'));
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
        try {
            $applications = Application::latest()->get();
            $users = User::where('status', 1)->latest()->get();
            $groups = Group::where(['status' => 1])
                ->latest()
                ->get();

            $selectedgroups = [];

            $selectedusers = [];
            return view('backend.notifications.create', compact('selectedgroups', 'selectedusers', 'users', 'groups', 'applications'));
        } catch (\Exception $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function addNotification(Request $request)
    {
        try {
            $rules = [
                'application_id' => 'required|exists:applications,id',
            ];

            $messages = [
                'application_id.required' => 'Application is required.',
                'application_id.exists' => 'Selected application does not exist.',
            ];

            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $type = $request->input('type');
            $applicationId = $request->input('application_id');
            $application = Application::find($applicationId);
            $applications = Application::latest()->get();

            $notification = Notification::all();
            $fields = Field::where('application_id', $applicationId)->where('status', 1)->get();

            $users = User::where('status', 1)->latest()->get();
            $groups = Group::where(['status' => 1])
                ->latest()
                ->get();
            $selectedgroups = [];

            $selectedusers = [];

            $selectedusersCc = [];
            return view('backend.notifications.storeNoti', compact('type', 'applicationId', 'applications', 'application', 'fields', 'selectedusers', 'selectedgroups', 'users', 'groups', 'selectedusersCc'));
        } catch (\Exception $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function createNotification()
    {
        try {
            $applications = Application::latest()->get();
            return view('backend.notifications.createNoti', compact('applications'));
        } catch (\Exception $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
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
            $rules = [
                'name' => 'required|unique:notifications,name',
            ];
            $messages = [
                'name.required' => 'The name field is required.',
                'name.unique' => 'The name field must be unique.',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);

            if ($validator->fails()) {
                throw ValidationException::withMessages($validator->errors()->toArray());
            }

            $data = $request->except(['_token', 'field_id', 'filter_operator', 'filter_value']);
            $data['active'] = $request->input('active') == 'Y' ? 'Y' : 'N';
            $notification = Notification::create($data);
            $filterFields = $request->input('field_id', []);
            $filterOperators = $request->input('filter_operator', []);
            $filterValues = $request->input('filter_value', []);

            if (!empty($filterFields) && !empty($filterValues) && count($filterFields) === count($filterValues)) {
                foreach ($filterFields as $index => $field) {
                    // Check if field_id is set and not null before proceeding
                    if (isset($field) && $field !== null) {
                        $operator = is_array($filterOperators) ? $filterOperators[$index] ?? null : null;
                        $value = is_array($filterValues) ? $filterValues[$index] ?? null : null;
                        FilterCriteria::create([
                            'notification_id' => $notification->id,
                            'field_id' => $field,
                            'filter_operator' => $operator,
                            'filter_value' => $value,
                        ]);
                    }
                }
            }
            Log::channel('custom')->info('Attachment Created by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' , Notification Name -> ' . $notification->name . ' , Data -> ' . json_encode($data));
            return redirect()->route('notifications.index')->with('success', 'Notification Created.');
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
        //using show for indexing in notifications
        try {
            //code...
            $notifications = Notification::where('application_id', $id)->latest()->get();
            $application = Application::find($id);
            // dd($notifications,  $application);
            return view('backend.notifications.index', compact('notifications', 'application'));
        } catch (\Exception $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
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
            $notification = Notification::find($id);
            $applications = Application::latest()->get();
            $fields = Field::where('application_id', $notification->application_id)
                ->where('status', 1)
                ->get();
            $filters = FilterCriteria::where('notification_id', $id)->get();

            $users = User::where('status', 1)->latest()->get();
            $groups = Group::where(['status' => 1])
                ->latest()
                ->get();

            $selectedgroups = [];
            if ($notification->group_list != 'null') {
                $groupids = json_decode($notification->group_list);
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
            if ($notification->user_list != 'null') {
                $userids = json_decode($notification->user_list);
                # code...
                if ($userids) {
                    for ($i = 0; $i < count($userids); $i++) {
                        # code...
                        $user = User::find($userids[$i]);
                        array_push($selectedusers, $user);
                    }
                }
            }

            $selectedusersCc = [];
            if ($notification->user_cc != 'null') {
                $userccids = json_decode($notification->user_cc);
                # code...
                if ($userccids) {
                    for ($i = 0; $i < count($userccids); $i++) {
                        # code...
                        $user = User::find($userccids[$i]);
                        array_push($selectedusersCc, $user);
                    }
                }
            }

            return view('backend.notifications.edit', compact('applications', 'notification', 'fields', 'selectedusers', 'selectedgroups', 'users', 'groups', 'filters', 'selectedusersCc'));
            // dd($audit);
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
    public function update(Request $request, $id)
    {
        try {
            $data = $request->all();
            unset($data['_token']);
            $data['active'] = $request->input('active') == 'Y' ? 'Y' : 'N';

            $notification = Notification::findOrFail($id);
            if ($notification) {
                $filterFields = $request->input('field_id', []);
                $filterOperators = $request->input('filter_operator', []);
                $filterValues = $request->input('filter_value', []);
                // dd($filterFields);
                if (!empty($filterFields)) {
                    foreach ($filterFields as $index => $field) {
                        // Validate that field_id is not null and exists in the database
                        if ($field !== null && Field::find($field)) {
                            $criteriaData = [
                                'field_id' => $field,
                                'filter_operator' => $filterOperators[$index] ?? null,
                                'filter_value' => $filterValues[$index] ?? null,
                            ];

                            // Use firstOrNew to find or create a FilterCriteria instance
                            $filterCriteria = $notification->filterCriterias()->firstOrNew(['field_id' => $field]);

                            // Update the existing record with the new data
                            $filterCriteria->fill($criteriaData)->save();
                        }
                    }
                }
                $notification->update($data);

                Log::channel('custom')->info('Attachment Created by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' , Notification Name -> ' . $notification->name . ' , Data -> ' . json_encode($data));
                return redirect()->route('notifications.index')->with('success', 'Notification updated successfully!');
                // return redirect()->route('notifications.index')->with('success', 'Notification updated successfully!');
            } else {
                // Handle case where the notification with the given ID is not found
                return redirect()->route('notifications.index')->with('error', 'Notification not found.');
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
            $notification = Notification::find($id);
            $notification->filterCriterias()->delete();
            Log::channel('custom')->info('Userid -> ' . auth()->user()->custom_userid . ' , Attachment Deleted by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' , Notification Name -> ' . $notification->name);
            // Notification::destroy($id)
            $notification->delete();
            return redirect()->back()->with('success', 'Successfully Notification Delete.');
            // dd($audit);
        } catch (\Exception $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    public function filtersDestroy($id)
    {
        try {
            $filterCriteria = FilterCriteria::findOrFail($id);
            $filterCriteria->delete();
            return redirect()->back()->with('success', 'FilterCriteria deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('success', 'Error deleting FilterCriteria');
        }
    }
    public function custom_edit($id)
    {
        try {
            //code...
            // dd($id);
            $notification = Notification::find($id);
            // dd($notification);
            $users = User::where('status', 1)->latest()->get();
            $groups = Group::where(['status' => 1])
                ->latest()
                ->get();

            $selectedgroups = [];
            // dd($notification->group_list);
            if ($notification->group_list != 'null') {
                $groupids = json_decode($notification->group_list);
                # code...
                for ($i = 0; $i < count($groupids); $i++) {
                    # code...
                    $group = Group::find($groupids[$i]);
                    array_push($selectedgroups, $group);
                }
            }

            $selectedusers = [];
            if ($notification->user_list != 'null') {
                $userids = json_decode($notification->user_list);
                # code...
                for ($i = 0; $i < count($userids); $i++) {
                    # code...
                    $user = User::find($userids[$i]);
                    array_push($selectedusers, $user);
                }
            }
            $selectedusersCc = [];
            if ($notification->user_cc != 'null') {
                $userccids = json_decode($notification->user_cc);
                # code...
                for ($i = 0; $i < count($userccids); $i++) {
                    # code...
                    $user = User::find($userccids[$i]);
                    array_push($selectedusersCc, $user);
                }
            }
            // dd($selectedusers, $selectedgroups);
            return view('backend.notifications.customedit', compact('selectedgroups', 'selectedusers', 'users', 'groups', 'notification', 'selectedusersCc'));
            // dd($audit);
        } catch (\Exception $th) {
            //throw $th;
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
