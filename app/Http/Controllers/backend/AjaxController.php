<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\backend\Application;
use App\Models\backend\Field;

class AjaxController extends Controller
{
    //
    public function change_forder(Request $request)
    {
        try {
            //code...
            $application = Application::find($request->application_id);
            $fields = Field::where('application_id', $application->id)
                ->orderBy('forder', 'asc')
                ->get();

            for ($i = 0; $i < count($fields); $i++) {
                # code...
                // dd($fields[$i]->forder, $request->forderarray[$i]);
                $fields[$i]->forder = $request->forderarray[$i];
                $fields[$i]->save();
            }
            // dd($fields);
            return response()->json(['success' => 'true']);
        } catch (\Exception $th) {
            return response()->json(['success' => 'false', 'error' => $th->getMessage()]);
        }
    }
}
