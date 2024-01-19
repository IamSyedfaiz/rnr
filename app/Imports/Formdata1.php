<?php

namespace App\Imports;

use App\Models\backend\Formdata;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\backend\Field;
use App\Models\backend\Group;

class Formdata1 implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $applicationid = session()->get('applicationid');
        $authuserid = auth()->id();
        $dbfields = Field::where(['application_id' => $applicationid, 'status' => 1])
            ->orderBy('forder', 'ASC')
            ->get();
        $fields = [];
        $userid = [];
        for ($i = 0; $i < count($dbfields); $i++) {
            # code...

            if ($dbfields[$i]->access == 'private') {
                # code...
                if ($dbfields[$i]->groups != 'null') {
                    array_push($userid, $this->findusers($dbfields[$i]->groups));
                    // if ($dbfields[$i]->rolestable()->first()->group_list != 'null') {
                    //     # code...
                    // }

                    // if ($dbfields[$i]->rolestable()->first()->user_list != 'null') {
                    //     # code...
                    //     array_push($userid, json_decode($dbfields[$i]->rolestable()->first()->user_list));
                    // }

                    $useridfound = 'false';
                    // dd(in_array(auth()->id(), $userid[2]));
                    for ($j = 0; $j < count($userid); $j++) {
                        if (in_array(auth()->id(), $userid[$j])) {
                            $useridfound = 'true';
                        }
                    }

                    if ($useridfound == 'true') {
                        array_push($fields, $dbfields[$i]);
                    }
                    // dd($fields);
                }
            } else {
                # code...
                array_push($fields, $dbfields[$i]);
            }
        }

        $datacount = count($fields);
        $finalarray = [];
        // dd($fields);
        // dd($fields[0]->name, $row[0]);
        for ($i = 0; $i < $datacount; $i++) {
            # code...
            $finalarray[$fields[$i]->name] = $row[$i];
            // dd($finalarray);
        }
        // dd($finalarray, $row, $datacount);
        return new Formdata([
            'userid' => $authuserid,
            'application_id' => $applicationid,
            'data' => json_encode($finalarray),
        ]);
    }

    public function findusers($data)
    {
        $array = json_decode($data);
        $userids = [];
        for ($i = 0; $i < count($array); $i++) {
            # code...
            $userarray = Group::find($array[$i]);
            $newarray = json_decode($userarray->userids);
            for ($j = 0; $j < count($newarray); $j++) {
                # code...
                array_push($userids, $newarray[$j]);
            }
            // array_merge($userids, json_decode($userarray->userids));
        }
        return $userids;
    }
}
