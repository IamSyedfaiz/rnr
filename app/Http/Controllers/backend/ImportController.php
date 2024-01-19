<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\Formdata1;
use App\Models\backend\Application;
use Illuminate\Support\Facades\Log;

class ImportController extends Controller
{
    public function getImport($id)
    {
        // dd($id);
        return view('backend.import.import', compact('id'));
    }

    public function parseImport(Request $request)
    {
        try {
            //code...
        } catch (\Exception $th) {
            //throw $th;
        }
        session()->put('applicationid', $request->application_id);
        $application = Application::find($request->application_id);
        $data = Excel::import(new Formdata1(), $request->file('file')->store('files'));
        Log::channel('custom')->info('Excel Import by ' . auth()->user()->name . ' ' . auth()->user()->lastname . ' Application Name -> ' . $application->name);

        // dd($data);

        return redirect()
            ->back()
            ->with('success', 'Successfully Updated.');
    }

    public function processImport(Request $request)
    {
        $data = CsvData::find($request->csv_data_file_id);
        $csv_data = json_decode($data->csv_data, true);
        foreach ($csv_data as $row) {
            $contact = new Contact();
            foreach (config('app.db_fields') as $index => $field) {
                $contact->$field = $row[$request->fields[$index]];
            }
            $contact->save();
        }

        return view('import_success');
    }
}
