<?php

namespace App\Http\Controllers\backend;

use App\Exports\ValidationExport;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Imports\ApplicationExcelImportClass;
use App\Models\backend\Application;
use App\Models\backend\Field;
use App\Models\backend\UrlExportImport;
use App\Models\backend\Formdata;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class IntegrationController extends Controller
{
    public function getFile()
    {
        // Specify the path of the file in the storage directory
        $filePath = storage_path('app/application/1705140124_data.csv');

        // Check if the file exists
        if (file_exists($filePath)) {
            return response()->stream(
                function () use ($filePath) {
                    readfile($filePath);
                },
                200,
                [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="1705140124_data.csv"',
                ],
            );
        }

        // If the file doesn't exist, you can return an error response
        return response()->json(['error' => 'File not found'], 404);
    }
    public function showForm($id)
    {
        return view('backend.integration.uploadFile', compact('id'));
    }
    public function dataFeed()
    {
        $applications = Application::latest()->get();
        return view('backend.integration.index', compact('applications'));
    }
    public function dataImports()
    {
        $applications = Application::latest()->get();
        return view('backend.integration.indexImport', compact('applications'));
    }
    public function showUrl($id)
    {
        $databaseColumns = Field::where('application_id', $id)
            ->where('status', 1)
            ->get();
        $dataUrls = UrlExportImport::where('application_id', $id)->first();
        return view('backend.integration.uploadLocalurl', compact('id', 'databaseColumns', 'dataUrls'));
    }
    public function importUpload(Request $request)
    {
        $request->validate([
            'excel_file' => 'required',
            'application_id' => 'required',
        ]);
        $applicationId = $request->input('application_id');
        $originalFilename = $request->file('excel_file')->getClientOriginalName();
        $uniqueFilename = time() . '_' . $originalFilename;

        $path = $request->file('excel_file')->storeAs('application', $uniqueFilename);

        return redirect()->route('review.import', ['path' => $uniqueFilename, 'id' => $applicationId]);
    }
    public function urlLocalUpload(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'excel_file' => 'required|string',
                'start_time' => 'nullable|',
                'start_day' => 'nullable|date',
                'recurring' => 'nullable|string',
                'scheduled_time' => 'nullable|',
                'scheduled_day' => 'nullable|integer|min:1|max:31',
                'selected_week_day' => 'nullable|string',
                'application_id' => 'nullable|string',
                'column_mappings' => 'nullable',
                'key_field' => 'nullable',
            ]);

            $sourcePath =  $request->input('excel_file');
            if (file_exists($sourcePath)) {
                $uniqueFileName = Str::random(10) . '.' . pathinfo($sourcePath, PATHINFO_EXTENSION);
                $data['file_name'] = $uniqueFileName;
                $destinationPath = 'application/' . $uniqueFileName;
                Storage::put($destinationPath, file_get_contents($sourcePath));
            } else {
                return redirect()->back()->with('error', 'Source file does not exist.');
            }
            // dd($data);
            $urlData = UrlExportImport::where('name', $data['name'])
                ->where('user_id', $request->input('user_id'))
                ->first();

            if ($urlData) {
                $urlData->column_mappings = json_encode($data['column_mappings']);
                $urlData->update($data);
                $message = 'Data updated successfully';
            } else {
                UrlExportImport::create($data + [
                    'user_id' => $request->input('user_id'),
                    'column_mappings' => json_encode($data['column_mappings']),
                ]);
                $message = 'Data saved successfully';
            }

            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            // Handle database operation exceptions
            return redirect()->back()->with('error', 'An error occurred while saving data.' . $e->getMessage());
        }
    }
    public function urlUpload(Request $request)
    {
        $request->validate([
            'excel_file' => 'required',
            'application_id' => 'required',
        ]);

        $applicationId = $request->input('application_id');
        $excelFileUrl = $request->input('excel_file');
        $fileContents = file_get_contents($excelFileUrl);
        $uniqueFilename = Str::random(5) . '.csv';
        Storage::disk('local')->put('application/' . $uniqueFilename, $fileContents);
        $fileUrl = Storage::url('app/application/' . $uniqueFilename);
        return redirect()->route('review.import', ['path' => $uniqueFilename, 'id' => $applicationId]);
    }

    public function reviewImport($path, $id)
    {

        $importedData = Excel::toArray($path, storage_path('app/application/' . $path));

        $dataFromFirstSheet = $importedData[0];

        // Check if the number of columns in the CSV file matches the expected number
        $expectedColumnsCount = Field::getExpectedColumnsCount($id);

        if (count($dataFromFirstSheet[0]) !== $expectedColumnsCount) {
            return redirect()
                ->back()
                ->with('error', 'Column count mismatch!');
        }
        $excelColumns = array_keys($dataFromFirstSheet[0]);
        $databaseColumns = Field::where('application_id', $id)
            ->where('status', 1)
            ->get();

        return view('backend.integration.column_mapping', compact('excelColumns', 'databaseColumns', 'path', 'dataFromFirstSheet', 'id'));
    }
    public function processImport(Request $request)
    {
        $path = $request->input('path');
        $id = $request->input('application_id');
        $columnMappings = $request->input('column_mappings');
        $importType = $request->input('import_type');
        $lookupField = $request->input('lookup_field');
        // dd($columnMappings);
        // $fieldDatas = Field::where('application_id', $id)
        //     ->where('status', 1)
        //     ->get();

        // $validationRules = [];
        // foreach ($fieldDatas as $field) {
        //     $rules = [];

        //     if ($field->requiredfield == 1) {
        //         $rules[] = 'required';
        //     }
        // if ($field->requireuniquevalue == 1) {
        //     $formDataCheck = Formdata::where('application_id', $id)->get();
        //     $jsonDataArray = [];

        //     foreach ($formDataCheck as $dataceck) {
        //         $jsonData = json_decode($dataceck->data, true);
        //         $jsonDataArray[] = $jsonData;
        //     }


        //     $importedData = Excel::toArray($path, storage_path('app/application/' . $path));


        //     // dd($jsonDataArray);
        //     // dd($importedData);
        //     $dataFromFirstSheet = $importedData[0];
        //     foreach ($dataFromFirstSheet as $importedData) {

        //         foreach ($jsonDataArray as $jsonData) {
        //             if ($this->isDataMatch($importedData, $jsonData)) {
        //                 return redirect()
        //                     ->back()
        //                     ->with('error', 'Duplicate data found!');
        //             }
        //         }
        //     }
        // }
        //     $validationRules[$field->name] = $rules;
        // }
        // $request->validate($validationRules);

        // dd($validation);
        // dd($fieldDatas);

        $import = new ApplicationExcelImportClass($columnMappings, $id, $importType, $lookupField);


        $filePath = storage_path('app/application/' . $path);

        if (!file_exists($filePath)) {
            return redirect()
                ->back()
                ->with('error', 'File not found');
        }
        try {
            Excel::import($import, $filePath);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error importing file: ' . $e->getMessage());
        }
        // dd($import);
        if (!empty($import->errorLog)) {
            // If there are errors, redirect back with error messages
            return redirect()->back()->with('errors', $import->errorLog);
        } else {
            // If there are no errors, show a success message
            return redirect()->back()->with('success', 'Import successful!');
        }
    }
    private function isDataMatch($array1, $array2)
    {
        $dataMatched = false;
        foreach ($array1 as $index => $value) {
            if (!is_int($index)) {
                continue;
            }
            foreach ($array2 as $key => $array2Value) {
                if ($array2Value == $value) {
                    $dataMatched = true;
                    return true;
                }
            }
        }

        return $dataMatched;
    }

    public function getCsvData(Request $request)
    {
        $excelFile = $request->input('excelFile');
        // $csvData = file_get_contents($excelFile);
        $csvData = array_map('str_getcsv', file($excelFile));
        return response()->json($csvData);
    }
}