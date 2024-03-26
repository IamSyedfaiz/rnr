<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;
use App\Models\backend\Group;

class Helper
{
    public static function findusers($data)
    {
        $array = json_decode($data);
        $userids = [];
        if ($array != null) {
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
        }
        return $userids;
    }
    public static function getValidation($id)
    {
        return $id;
    }
}
