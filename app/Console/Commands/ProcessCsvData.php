<?php

namespace App\Console\Commands;

use App\Models\backend\Formdata;
use App\Models\backend\UrlExportImport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\backend\Field;
use Illuminate\Support\Facades\Log;
class ProcessCsvData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process CSV data and save';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dataToProcess = UrlExportImport::all();
        foreach ($dataToProcess as $item) {
            $this->processCsvData($item);
            $startDateTime = Carbon::parse($item->start_day . ' ' . $item->start_time);
            if (Carbon::now() >= $startDateTime) {
                if ($item->recurring == 'minutely' && Carbon::now()->format('s') == '00') {
                    $this->processCsvData($item);
                } elseif ($item->recurring == 'hourly' && Carbon::now()->format('i') == '00') {
                    $this->processCsvData($item);
                } elseif ($item->recurring == 'daily' && $item->scheduled_time == Carbon::now()->format('H:i:s')) {
                    $this->processCsvData($item);
                } elseif ($item->recurring == 'weekly' && $item->scheduled_time == Carbon::now()->format('H:i:s') && $item->selected_week_day == Carbon::now()->format('l')) {
                    $this->processCsvData($item);
                } elseif ($item->recurring == 'monthly' && $item->scheduled_time == Carbon::now()->format('H:i:s') && $item->scheduled_day == Carbon::now()->format('d')) {
                    $this->processCsvData($item);
                }

                // $csvFilePath = 'application/' . $item->file_name;
                // $csvData = Storage::get($csvFilePath);
                // $csvLines = explode("\n", $csvData);
                // $header = str_getcsv(array_shift($csvLines));
                // foreach ($csvLines as $line) {
                //     $row = str_getcsv($line);
                //     $rowData = array_combine($header, $row);
                //     $dataExist = Formdata::where('data->' . $item->key_field, $rowData[$item->key_field])->first();
                //     logger($dataExist);
                //     if ($dataExist) {
                //         $dataExist->update([
                //             'data' => json_encode($rowData),
                //             'userid' => $item->user_id,
                //             'application_id' => $item->application_id,
                //         ]);
                //     } else {
                //         Formdata::create([
                //             'data' => json_encode($rowData),
                //             'userid' => $item->user_id,
                //             'application_id' => $item->application_id,
                //         ]);
                //     }
                // }
            }
        }
        $this->info('End Loop');
    }
    private function processCsvData($item)
    {
        $csvFilePath = 'application/' . $item->file_name;
        $csvData = Storage::get($csvFilePath);
        $csvLines = explode("\n", $csvData);
        $header = str_getcsv(array_shift($csvLines));
        foreach ($csvLines as $line) {
            $row = str_getcsv($line);
            $rowData = array_combine($header, $row);
            $field = Field::where('application_id', $item->application_id)
                ->where('name', $header)
                ->where('status', 1)
                ->first();
            $formExist = Formdata::where('data->' . $field->name, $rowData[$item->key_field])->first();
            $dataArray = json_decode($formExist->data, true);
            $this->info('--1 baar--');
            if ($formExist && $field->requiredfield == 1) {
                // Iterate over the arrays and compare each element
                foreach ($rowData as $key => $value) {
                    if ($value == null) {
                        Log::channel('user')->error("Value for required key '$key' is null");
                        logger("Value for required key '$key' is null");
                        exit();
                    }
                }
            }
            if ($formExist && $field->requireuniquevalue == 1) {
                foreach ($rowData as $key => $value) {
                    if (isset($dataArray[$key]) && $dataArray[$key] === $value) {
                        Log::channel('user')->error("Value for key '$key' matches: $value");
                        exit();
                    }
                }
            }
            $dataExist = Formdata::where('data->' . $item->key_field, $rowData[$item->key_field])->first();
            if ($dataExist) {
                $dataExist->update([
                    'data' => json_encode($rowData),
                    'userid' => $item->user_id,
                    'application_id' => $item->application_id,
                ]);
            } else {
                Formdata::create([
                    'data' => json_encode($rowData),
                    'userid' => $item->user_id,
                    'application_id' => $item->application_id,
                ]);
            }
        }
        Log::channel('user')->info("Feed '$item->name' is being imported.");
    }
}
