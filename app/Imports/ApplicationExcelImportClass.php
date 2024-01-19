<?php

namespace App\Imports;

use App\Models\backend\Application;
use App\Models\backend\Field;
use App\Models\backend\Formdata;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Auth;

class ApplicationExcelImportClass implements ToCollection
{
    protected $columnMappings;
    protected $id;
    protected $lookupField;
    protected $importType;
    public $errorLog = [];
    public function __construct($columnMappings, $id, $importType, $lookupField)
    {
        $this->lookupField = $lookupField;
        $this->importType = $importType;
        $this->columnMappings = $columnMappings;
        $this->id = $id;
    }
    /**
     * @param Collection $collection
     */

    public function collection(Collection $rows)
    {
        $error = 0;
        $return['success'] = '';
        $return['error'] = '';

        $userId = Auth::id();
        $dataRows = $rows->slice(1);
        foreach ($dataRows as $row) {
            $mappedData = [];
            $FormData = [];

            // dd($rows);
            foreach ($this->columnMappings as $excelColumn => $databaseColumn) {
                // logger($excelColumn);
                $field = Field::where('application_id', $this->id)
                    ->where('name', $databaseColumn)
                    ->where('status', 1)
                    ->first();
                $formExist = Formdata::where('data->' . $field->name, $row[$excelColumn])->first();
                if ($field->requiredfield == 1 && !isset($row[$excelColumn])) {
                    $this->errorLog[] = $field->name . ' is required';
                    $error++;
                }

                // if (!empty($excelColumn) && !empty($databaseColumn) && isset($row[$excelColumn])) {
                if (isset($row[$excelColumn])) {
                    // Additional Validation based on Field Type
                    // if ($field->requireuniquevalue == 1 && $formExist) {
                    //     $dataExist = Formdata::where('data->id', $row[$excelColumn])->first();
                    //     $this->errorLog[] = 'Unique field for ' . $field->name;
                    //     $error++;
                    // }
                    if ($field->type == 'number' && !is_numeric($row[$excelColumn])) {
                        $this->errorLog[] = 'Invalid value for ' . $field->name;
                        $error++;
                    } elseif ($field->type == 'text' && !is_string($row[$excelColumn])) {
                        // $this->errorLog[] = $row[$excelColumn];
                        $this->errorLog[] = 'Invalid value for ' . $field->name;
                        $error++;
                    } elseif ($field->type == 'date' && !strtotime($row[$excelColumn])) {
                        $this->errorLog[] = 'Invalid value for ' . $field->name;
                        $error++;
                    }
                    $mappedData[$databaseColumn] = $row[$excelColumn];
                } else {
                    $mappedData[$databaseColumn] = null;
                }
            }

            $FormData['data'] = json_encode($mappedData);
            $FormData['userid'] = $userId;
            $FormData['application_id'] = $this->id;
            $importType = $this->importType;
            $lookupField = $this->lookupField;
            logger('----');
            logger($field->requireuniquevalue);
            logger('----');
            if ($importType == 'create_new') {
                Formdata::create($FormData);
            } elseif ($importType == 'update_existing') {
                $dataExist = Formdata::where('data->' . $lookupField, $mappedData[$lookupField])->first();

                if ($dataExist && $field->requireuniquevalue == 1) {
                    $existingData = json_decode($dataExist->data, true);
                    if ($this->isDataMatch($mappedData, $existingData, 'id')) {
                        $this->errorLog[] = 'Unique field for ' . $field->name;
                        $error++;
                    }
                }
                if ($dataExist) {
                    $dataExist->update($FormData);
                }
            } else {
                $this->errorLog[] = 'Please Select Import Type';
                $error++;
            }

            // $dataExist = Formdata::where('data->id', $mappedData['id'])->first();
            // if ($dataExist && $field->requireuniquevalue == 1) {
            //     $existingData = json_decode($dataExist->data, true);
            //     if ($this->isDataMatch($mappedData, $existingData, 'id')) {
            //         $this->errorLog[] = 'Unique field for ' . $field->name;
            //         $error++;
            //     }
            // }

            if ($error > 0) {
                return true;
            }

            // if ($dataExist) {
            //     $dataExist->update($FormData);
            // } else {
            //     Formdata::create($FormData);
            // }
        }
    }
    private function isDataMatch($data1, $data2, $excludeKey = null)
    {
        $filteredData1 = $this->excludeField($data1, $excludeKey);
        $filteredData2 = $this->excludeField($data2, $excludeKey);

        return $filteredData1 == $filteredData2;
    }

    private function excludeField($data, $excludeKey)
    {
        if ($excludeKey && isset($data[$excludeKey])) {
            unset($data[$excludeKey]);
        }
        return $data;
    }
}
