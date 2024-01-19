<?php

namespace App\Traits;

use Illuminate\Http\Request;
use the42coders\Workflows\Workflow;
use Illuminate\Support\Facades\Log;
use the42coders\Workflows\Tasks\Task;

trait WorkflowTraits
{
    public function workflow($tasks){
        dd($tasks);
        for ($i = 0; $i < count($tasks); $i++) {
                # code...
                if ($tasks[$i]->name == 'Stop') {
                    # code...
                    $parenttask = Task::where('id', $tasks[$i]->parentable_id)->first();
                    dd($parenttask);

                }
                if ($tasks[$i]->name == 'SendNotification') {
                    # code...
                    $sendmail = false;
                    $wdata = json_decode($tasks[$i]->data_fields);
                    // dd($data);
                    $subject = $wdata->name;
                    $notification = $wdata->notification;


                }
             
                $parenttask = Task::where('id', $tasks[$i]->parentable_id)->first();
                // // dd($parenttask);
                // if (isset($parenttask->name) && $parenttask->name == 'EvaluateContent') {
                //     $wdata1 = json_decode($parenttask->data_fields);
                //     // dd($wdata1);
                //     for ($j = 0; $j < count($wdata1->fieldname); $j++) {
                //         # code...
                //         if (array_key_exists($wdata1->fieldname[$j], $data)) {
                //             # code...
                //             // dd($wdata1->fieldname[$j], $data);
                //             if ($wdata1->operators[$j] == 'equal') {
                //                 # code...
                //                 if ($data[$wdata1->fieldname[$j]] == $wdata1->values[$j]) {
                                    
                //                     # code...
                //                     // dd($notification);
                //                     $mailsend = Mail::send('email.useraction', ['data' => $notification], function ($message) use($notification, $subject) {
                //                         $message->sender('jakpower@omegawebdemo.com.au');
                //                         $message->subject($subject);
                //                         $message->to(auth()->user()->email);
                //                     });
                //                 }
                //             }
                //         }
                //     }
                // }
            }
    }

}
