<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\backend\Application;
use App\Models\backend\Attachments;
use App\Models\backend\Field;
use App\Models\backend\Group;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        try {
            //code...
            $applications = Application::latest()->get();
            return view('backend.applications.index', compact('applications'));
        } catch (\Exception $th) {
            //throw $th;
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
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
            //code...
            return view('backend.applications.create');
        } catch (\Exception $th) {
            //throw $th;
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
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
                'name' => 'required|unique:applications',
                // 'attachments' => 'required|mimes:pdf,jpg,png|min:5|max:2048',
                'status' => 'required',
                // 'description' => 'required',
            ];

            $custommessages = [
                'name.unique' => 'Application Name Should be Unique.'
            ];

            $this->validate($request, $rules, $custommessages);
            //code...
            $data = $request->all();
            // dd($data);
            // $data['access'] = 'public';
            unset($data['_token']);
            if ($request->attachments) {
                $attachments = [];
                # code...
                foreach ($request->file('attachments') as $image) {
                    $filename = rand() . $image->getClientOriginalName();
                    $destination_path = public_path('/application');
                    $image->move($destination_path, $filename);
                    array_push($attachments, $filename);
                }
                $data['attachments'] = json_encode($attachments);
            }
            // dd($data);
            $application = Application::create($data);
            // dd('Userid ' . auth()->user()->custom_userid . ' Application Created by ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' Application Name -> ' . $application->name . ', Application Status -> ' . $application->status);
            Log::channel('custom')->info('Userid -> ' . auth()->user()->custom_userid . ' , Application Created by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' , Application Name -> ' . $application->name);
            return redirect()
                ->route('application.index')
                ->with('success', 'Application Created.');
        } catch (\Exception $th) {
            //throw $th;
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
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
            $application = Application::find($id);
            // dd($application->attachments);
            $attachments = Attachments::where('application_id', $id)
                ->latest()
                ->get();
            $fields = Field::where('application_id', $id)
                ->orderBy('forder', 'ASC')
                ->get();
            // dd($fields);

            $users = User::where('status', 1)
                ->latest()
                ->get();

            $groups = Group::where(['status' => 1])
                ->latest()
                ->get();

            $selectedgroups = [];
            if ($application->groups != null) {
                $groupids = json_decode($application->groups);
                # code...
                for ($i = 0; $i < count($groupids); $i++) {
                    # code...
                    $group = Group::find($groupids[$i]);
                    array_push($selectedgroups, $group);
                }
            }
            // dd($attachments);
            return view('backend.applications.edit', compact('selectedgroups', 'users', 'groups', 'application', 'attachments', 'fields'));
            // dd($audit);
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
        try {
            //code...
            // dd($request->all(), $request->application_id);

            if ($request->application_id) {
                $rules = [
                    'attachments' => 'required|mimes:pdf,jpg,png|min:5|max:2048',
                ];

                $custommessages = [];

                $this->validate($request, $rules, $custommessages);
                # code...
                // dd($request->all());
                $data['name'] = $request->attachments->getClientOriginalName();
                $data['imagename'] = rand() . $request->attachments->getClientOriginalName();
                $data['size'] = round($request->attachments->getSize() / 1024, 4) . 'KB';
                $data['type'] = $request->attachments->getMimeType();
                $data['application_id'] = $request->application_id;
                $destination_path = public_path('/application');
                $request->attachments->move($destination_path, $data['imagename']);
                // dd($data);
                $application = Application::find($request->application_id);
                $attachment = Attachments::create($data);
                Log::channel('custom')->info('Userid -> ' . auth()->user()->custom_userid . ' , Attachment Created by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' Application Name -> ' . $application->name);
                if ($attachment) {
                    # code...
                    return redirect()
                        ->back()
                        ->with('success', 'Successfully Attachments Create.');
                } else {
                    # code...
                    return redirect()
                        ->back()
                        ->with('error', 'Technical Error.');
                }
            } else {
                # code...
                // dd('prateek');
                $data = $request->all();
                // dd($data);
                unset($data['_token']);
                unset($data['_method']);
                unset($data['groups']);

                $application = Application::find($id);
                if ($request->groups != null) {
                    # code...
                    $application->groups = json_encode($request->groups);
                }

                if ($request->access == 'public') {
                    # code...
                    $data['groups'] = null;
                }

                $changearray = [];
                if ($application->status == 1) {
                    # code...
                    $currentstatus = 'Active';
                } else {
                    # code...
                    $currentstatus = 'InActive';
                }
                $currentarray = [
                    'name' => $application->name,
                    'description' => $application->description,
                    'status' => $currentstatus,
                ];

                if ($application->name != $data['name']) {
                    # code...
                    $changearray['name'] = $data['name'];
                }

                if ($application->status != $data['status']) {
                    # code...
                    if ($data['status'] == 1) {
                        # code...
                        $changearray['status'] = 'Active';
                    } else {
                        # code...
                        $changearray['status'] = 'InActive';
                    }
                }

                if ($application->description != $data['description']) {
                    # code...
                    $changearray['description'] = $data['description'];
                }

                // dd($changearray);

                $application->update($data);
                Log::channel('custom')->info('Userid -> ' . auth()->user()->custom_userid . ' , Application Edited by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' , Application Name -> ' . $application->name . ' , Current data -> ' . json_encode($currentarray) . ' , Changed data -> ' . json_encode($changearray));

                if ($application) {
                    # code...
                    return redirect()
                        ->back()
                        ->with('success', 'Successfully Application Edit.');
                } else {
                    # code...
                    return redirect()
                        ->back()
                        ->with('error', 'Technical Error.');
                }
            }
        } catch (\Exception $th) {
            //throw $th;
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
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
            //code...
            $application = Application::find($id);
            Log::channel('custom')->info('Userid -> ' . auth()->user()->custom_userid . ' , Application Deleted by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' , Application Name -> ' . $application->name);
            Application::destroy($id);

            return redirect()
                ->back()
                ->with('success', 'Successfully Application Delete.');
            // dd($audit);
        } catch (\Exception $th) {
            //throw $th;
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
        }
    }

    public function attachment_delete($id)
    {
        try {
            //code...
            // dd($id);
            $attachment = Attachments::find($id);
            Log::channel('custom')->info('Userid -> ' . auth()->user()->custom_userid . ' , Attachment Deleted by -> ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' , Attachment Name -> ' . $attachment->name);
            Attachments::destroy($id);

            return redirect()
                ->back()
                ->with('success', 'Successfully Attachments Delete.');
            // dd($audit);
        } catch (\Exception $th) {
            //throw $th;
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
        }
    }
}
