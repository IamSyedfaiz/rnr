<?php

namespace App\Console\Commands;

use App\Models\backend\Group;
use App\Models\backend\Notification;
use App\Models\backend\Field;
use App\Models\backend\FilterCriteria;
use App\Models\backend\Formdata;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendScheduledEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send scheduled emails';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $notifications = Notification::where('active', 'Y')->get();

        foreach ($notifications as $key => $notification) {
            // $template = $notification->body;
            // $Formdata01 = Formdata::where('application_id', $notification->application_id)->get();

            // $parsedData = collect(json_decode($Formdata01, true));
            // $replacedTemplates = [];
            // $parsedData->each(function ($entry) use ($template) {
            //     $data = json_decode($entry['data'], true);

            //     // Replace placeholders with values
            //     $replacedTemplate = $template;
            //     foreach ($data as $key => $value) {
            //         $placeholder = "[field:$key]";
            //         $replacedTemplate = str_replace($placeholder, $value, $replacedTemplate);
            //     }

            //     $replacedTemplates = $replacedTemplate;
            //     logger($replacedTemplate);
            // });
            // logger($replacedTemplates);
            // $data['body'] = $replacedTemplates;
            // logger($data);

            $template = $notification->body;
            $Formdata01 = Formdata::where('application_id', $notification->application_id)->get();

            $parsedData = collect(json_decode($Formdata01, true));
            $replacedTemplates = [];

            $parsedData->each(function ($entry) use ($template, &$replacedTemplates) {
                $data = json_decode($entry['data'], true);

                // Replace placeholders with values
                $replacedTemplate = $template;

                foreach ($data as $key => $value) {
                    $placeholder = "[field:$key]";
                    $replacedTemplate = str_replace($placeholder, $value, $replacedTemplate);
                }

                $replacedTemplates[] = $replacedTemplate;
                logger($replacedTemplate);
            });

            logger($replacedTemplates);
            $data['body'] = $replacedTemplates;
            Mail::send('email.loginmail', @$data, function ($msg) use ($notification) {
                $msg->from(env('MAIL_FROM_ADDRESS'));
                $msg->to('kixopa7992@regapts.com', env('MAIL_FROM_NAME'));
                $msg->subject($notification->subject);
            });

            $sendMail = false;
            if ($notification->recurring == 'daily' && now()->format('H:i') === $notification->scheduled_time) {
                $sendMail = true;
                $this->info('daily');
            } elseif ($notification->recurring == 'weekly' && now()->englishDayOfWeek == $notification->selected_week_day) {
                $sendMail = true;
                $this->info('weekly');
            } elseif ($notification->recurring == 'monthly' && now()->day == $notification->selected_day && now()->format('H:i') === $notification->scheduled_time) {
                $sendMail = true;
                $this->info('monthly');
            } elseif ($notification->recurring == 'quarterly' && now()->month % 3 === 0 && now()->format('H:i') === $notification->scheduled_time) {
                $sendMail = true;
                $this->info('quarterly');
            } elseif ($notification->recurring == 'reminder' && now()->format('H:i') === $notification->scheduled_time) {
                $filterCriterias = FilterCriteria::where('notification_id', $notification->id)->get();
                $fieldids = $filterCriterias->pluck('filter_value', 'field_id')->toArray();

                $filterCriterias = FilterCriteria::where('notification_id', $notification->id)->get();
                $dataExist = Formdata::where('application_id', $notification->application_id)
                    ->latest()
                    ->first();
                if ($dataExist) {
                    $dataArray = json_decode($dataExist['data'], true); // Convert to array
                    // logger($dataArray);
                }

                if ($filterCriterias) {
                    foreach ($filterCriterias as $key => $filterCriteria) {
                        if ($filterCriteria->filter_operator == 'C' && $filterCriteria->filter_value == $dataArray[$filterCriteria->field->name]) {
                            $sendMail = true;
                            logger('--c---');
                        } elseif ($filterCriteria->filter_operator == 'DNC' && $filterCriteria->filter_value !== request()->input($filterCriteria->field->name)) {
                            $sendMail = true;
                            logger('--DNC---');
                        } elseif ($filterCriteria->filter_operator == 'E' && $filterCriteria->filter_value === request()->input($filterCriteria->field->name)) {
                            $sendMail = true;
                            logger('--e---');
                        } elseif ($filterCriteria->filter_operator == 'DNE' && $filterCriteria->filter_value == request()->input($filterCriteria->field->name)) {
                            $sendMail = true;
                            logger('--DNE---');
                        } elseif ($filterCriteria->filter_operator == 'CH' && $filterCriteria->filter_value == request()->input($filterCriteria->field->name)) {
                            $sendMail = true;
                            logger('--DNE---');
                        } elseif ($filterCriteria->filter_operator == 'CT' && $filterCriteria->filter_value == request()->input($filterCriteria->field->name)) {
                            $sendMail = true;
                            logger('--DNE---');
                        } elseif ($filterCriteria->filter_operator == 'CF' && $filterCriteria->filter_value == request()->input($filterCriteria->field->name)) {
                            $sendMail = true;
                            logger('--DNE---');
                        }
                    }
                }

                $this->info('reminder');
            }
            $this->info('---');
            $this->info($sendMail);
            // if ($sendMail) {
            //     $selectedGroups = [];
            //     if ($notification->group_list != 'null') {
            //         $groupIds = json_decode($notification->group_list);

            //         if ($groupIds) {
            //             foreach ($groupIds as $groupId) {
            //                 $group = Group::find($groupId);

            //                 if ($group) {
            //                     $selectedGroups[] = $group->userids;
            //                 }
            //             }
            //         }
            //     }
            //     foreach ($selectedGroups as $value) {
            //         $this->info('--Groups');

            //         $this->info($value);
            //     }
            //     $userGroups = [];
            //     foreach ($selectedGroups as $groupUserIds) {
            //         // Decode the JSON string to an array
            //         $groupUserIdsArray = json_decode($groupUserIds, true);

            //         // Check if $groupUserIdsArray is an array
            //         if (!is_array($groupUserIdsArray)) {
            //             $this->error('Invalid data for groupUserIds: ' . $groupUserIds);
            //             continue; // Skip to the next iteration if data is invalid
            //         }

            //         foreach ($groupUserIdsArray as $userId) {
            //             // Check if $userId is a valid integer
            //             if (!is_numeric($userId) || intval($userId) <= 0) {
            //                 $this->error("Invalid user ID: $userId");
            //                 continue; // Skip to the next iteration if user ID is invalid
            //             }

            //             // Find the user by ID
            //             $user = User::find(intval($userId));

            //             if ($user) {
            //                 $userGroups[] = $user->email;
            //             } else {
            //                 $this->error("User not found for ID: $userId");
            //             }
            //         }
            //     }

            //     $selectedUsers = [];
            //     if ($notification->user_list != 'null') {
            //         $UserIds = json_decode($notification->user_list);

            //         if ($UserIds) {
            //             foreach ($UserIds as $UserId) {
            //                 $User = User::find($UserId);

            //                 if ($User) {
            //                     $selectedUsers[] = $User->email;
            //                 }
            //             }
            //         }
            //     }

            //     // foreach ($userGroups as $value) {
            //     //     $this->info($value);
            //     // }
            //     // foreach ($selectedUsers as $value) {
            //     //     $this->info('--user');
            //     //     $this->info($value);
            //     // }

            //     $data['body'] = $notification->body;
            //     if ($userGroups) {
            //         foreach ($userGroups as $recipient) {
            //             Mail::send('email.loginmail', @$data, function ($msg) use ($recipient, $notification) {
            //                 $msg->from(env('MAIL_FROM_ADDRESS'));
            //                 $msg->to($recipient, env('MAIL_FROM_NAME'));
            //                 $msg->subject($notification->subject);
            //             });
            //         }
            //     }
            //     if ($selectedUsers) {
            //         foreach ($selectedUsers as $recipient) {
            //             Mail::send('email.loginmail', @$data, function ($msg) use ($recipient, $notification) {
            //                 $msg->from(env('MAIL_FROM_ADDRESS'));
            //                 $msg->to($recipient, env('MAIL_FROM_NAME'));
            //                 $msg->subject($notification->subject);
            //             });
            //         }
            //     }
            // }
        }

        $this->info('Scheduled email sent successfully.');
    }
}
