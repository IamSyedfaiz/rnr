<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\backend\Audit;
use Illuminate\Support\Facades\Log;

class AuditController extends Controller
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
            $audits = Audit::latest()->get();
            return view('backend.audit.index', compact('audits'));
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
        return view('backend.audit.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'compliance' => 'required|mimes:pdf,jpg,png|min:5|max:2048',
            'report_date' => 'required|date',
            'expiry_date' => 'required|date',
            'sharewith' => 'required|email',
        ];

        $custommessages = [];

        $this->validate($request, $rules, $custommessages);
        try {
            $data = $request->all();
            // dd($request->all());
            unset($data['_token']);
            if ($request->compliance) {
                # code...
                $image = $request->compliance;
                $filename = rand() . $image->getClientoriginalName();
                $destination_path = public_path('/audit');
                $image->move($destination_path, $filename);
                $data['compliance'] = $filename;
            }
            $audit = Audit::create($data);
            if ($audit) {
                # code...
                return redirect()
                    ->route('audits.index')
                    ->with('success', 'Successfully Audit Created.');
            } else {
                # code...
                return redirect()
                    ->back()
                    ->with('error', 'Technical Error.');
            }
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
            $audit = Audit::find($id);
            return view('backend.audit.edit', compact('audit'));
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
            $data = $request->all();
            unset($data['_token']);
            unset($data['_method']);
            if ($request->compliance) {
                # code...
                $image = $request->compliance;
                $filename = rand() . $image->getClientoriginalName();
                $destination_path = public_path('/audit');
                $image->move($destination_path, $filename);
                $data['compliance'] = $filename;
            }

            $audit = Audit::find($id);
            $audit->update($data);
            if ($audit) {
                # code...
                return redirect()
                    ->back()
                    ->with('success', 'Successfully Audit Edit.');
            } else {
                # code...
                return redirect()
                    ->back()
                    ->with('error', 'Technical Error.');
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
        //
        try {
            //code...
            $audit = Audit::destroy($id);
            return redirect()
                ->back()
                ->with('success', 'Successfully Audit Delete.');
            // dd($audit);
        } catch (\Exception $th) {
            //throw $th;
            return redirect()
                ->back()
                ->with('error', $th->getMessage());
        }
    }
}
