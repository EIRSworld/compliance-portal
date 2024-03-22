<?php

namespace App\Console\Commands;

use App\Models\ComplianceSubMenu;
use App\Models\CompliantMenuDetail;
use App\Models\Country;
use App\Models\UploadDocument;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class FileRemainder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:file-remainder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $today = now()->startOfDay(); // Only the date part for comparison

        $uploadDocuments = UploadDocument::whereNotNull('expired_date')
            ->where('is_expired', '=', '0') // Assuming you want documents that are NOT yet expired
            ->get();

        foreach ($uploadDocuments as $document) {
            $expiredDate = Carbon::parse($document->expired_date);
            // Adding intervals for 4 weeks, 3 weeks, 2 weeks, 1 week, 1 day before, and 1 day after
            $intervals = [42, 35, 28, 21, 14, 7, 1, -1]; // Added days for the additional intervals

            foreach ($intervals as $interval) {
                if ($interval == -1) { // Special case for 1 day after
                    $reminderDate = $expiredDate->copy()->addDay();
                } else {
                    $reminderDate = $expiredDate->copy()->subDays($interval);
                }

                if ($today->equalTo($reminderDate)) {
                    if ($interval == -1) {
                        // Fetch users with 'management' role for 1 day after the expiry date
                        $users = User::role('management')->get();
                    } else {
                        // For other reminders, fetch users with the specified compliance roles
                        $users = User::role(['Compliance Finance Officer', 'Compliance Principle Officer'])->get();
                    }

                    foreach ($users as $user) {
                        $emails = $user->email; // Assuming this is the user's email address
                        $timeFrame = $interval == -1 ? '1 day after' : abs($interval) . ' day(s) before';
                        $data = [
                            'subject' => 'Compliance Reminder Email',
                            // You might want to customize this message further based on the recipient's role
                            'message' => "Your compliance document with ID: {$document->id} is nearing its expiry date on {$document->expired_date}. Reminder: $timeFrame.",
                        ];

                        // Sending email
                        Mail::send('emails.reminder', $data, function ($message) use ($data, $emails) {
                            $message->to($emails)
                                ->cc(['harish@nordicsolutions.in'])
                                ->subject($data['subject']);
                        });
                    }
                }
            }
        }

//        $uploadDocument = UploadDocument::whereNotNull('expired_date')->where('is_expired', '=', '1')
//            ->groupBy('country_id')->unique('expired_date')->get();
//
//
//        $users = User::role(['Compliance Finance Officer', 'Compliance Principle Officer'])->get();
//
//        foreach ($users as $user) {
//            $data = ['subject' => 'Compliance Reminder Email'];
//            $emails = $user->email; // Assuming $user->email contains the user's email address
//
//            Mail::send('mail.remainder-mail', $data, function ($message) use ($data, $emails) {
//                $message->to($emails, config('app.name'))
//                    ->cc(['harish@nordicsolutions.in'])
//                    ->subject($data['subject']);
//            });
//        }





//
//        foreach ($compliantSubMenus as $compliantSubMenu) {
//
//            $compliantExpiredDate = $compliantSubMenu->expired_date;
//
//
//
//            $users = User::role('country_head')->whereJsonContains('country_id', (string)$compliantSubMenu->country_id)->get();
//            foreach ($users as $user) {
//                $data = [
//                    'subject' => 'Reminder Email',
//                    'email' => $user->email,
//                    'user' => $user,
//                    'complaint' => $compliantSubMenu,
//                ];
//
//                Mail::send('mail.remainder-mail', $data, function ($message) use ($data) {
//                    $message->to($data['email'], config('app.name'))
//                        ->cc(['arjun@nordicsolutions.in'])
//                        ->subject('Reminder Mail');
//                });
//            }
//
//
//            if ($compliantExpiredDate && Carbon::now()->startOfDay()->eq(Carbon::parse($compliantExpiredDate)->subWeeks(6)->startOfDay())) {
//                $users = User::role('country_head')->whereJsonContains('country_id', (string)$compliantSubMenu->country_id)->get();
////                foreach ($users as $user) {
////                    $data = [
////                        'subject' => 'Reminder Email',
////                        'email' => $user->email,
////                        'user' => $user,
////                        'complaint' => $compliantSubMenu,
////                    ];
////
////                    Mail::send('mail.remainder-mail', $data, function ($message) use ($data) {
////                        $message->to($data['email'], config('app.name'))
////                            ->cc(['arjun@nordicsolutions.in'])
////                            ->subject('Reminder Mail');
////                    });
////                }
////            }
////            elseif ($compliantExpiredDate && Carbon::now()->startOfDay()->eq(Carbon::parse($compliantExpiredDate)->subWeeks(5)->startOfDay())) {
////                $users = User::role('country_head')->whereJsonContains('country_id', (string)$compliantSubMenu->country_id)->get();
////                foreach ($users as $user) {
////                    $data = [
////                        'subject' => 'Reminder Email',
////                        'email' => $user->email,
////                        'user' => $user,
////                        'complaint' => $compliantSubMenu,
////                    ];
////
////                    Mail::send('mail.remainder-mail', $data, function ($message) use ($data) {
////                        $message->to($data['email'], config('app.name'))
////                            ->cc(['arjun@nordicsolutions.in'])
////                            ->subject('Reminder Mail');
////                    });
////                }
////            }
////            elseif ($compliantExpiredDate && Carbon::now()->startOfDay()->eq(Carbon::parse($compliantExpiredDate)->subWeeks(4)->startOfDay())) {
////                $users = User::role('country_head')->whereJsonContains('country_id', (string)$compliantSubMenu->country_id)->get();
////                foreach ($users as $user) {
////                    $data = [
////                        'subject' => 'Reminder Email',
////                        'email' => $user->email,
////                        'user' => $user,
////                        'complaint' => $compliantSubMenu,
////                    ];
////
////                    Mail::send('mail.remainder-mail', $data, function ($message) use ($data) {
////                        $message->to($data['email'], config('app.name'))
////                            ->cc(['arjun@nordicsolutions.in'])
////                            ->subject('Reminder Mail');
////                    });
////                }
////            }
////            elseif ($compliantExpiredDate && Carbon::now()->startOfDay()->eq(Carbon::parse($compliantExpiredDate)->subWeeks(3)->startOfDay())) {
////                $users = User::role('country_head')->whereJsonContains('country_id', (string)$compliantSubMenu->country_id)->get();
////                foreach ($users as $user) {
////                    $data = [
////                        'subject' => 'Reminder Email',
////                        'email' => $user->email,
////                        'user' => $user,
////                        'complaint' => $compliantSubMenu,
////                    ];
////
////                    Mail::send('mail.remainder-mail', $data, function ($message) use ($data) {
////                        $message->to($data['email'], config('app.name'))
////                            ->cc(['arjun@nordicsolutions.in'])
////                            ->subject('Reminder Mail');
////                    });
////                }
////            }
////            elseif ($compliantExpiredDate && Carbon::now()->startOfDay()->eq(Carbon::parse($compliantExpiredDate)->subWeeks(2)->startOfDay())) {
////                $users = User::role('country_head')->whereJsonContains('country_id', (string)$compliantSubMenu->country_id)->get();
////                foreach ($users as $user) {
////                    $data = [
////                        'subject' => 'Reminder Email',
////                        'email' => $user->email,
////                        'user' => $user,
////                        'complaint' => $compliantSubMenu,
////                    ];
////
////                    Mail::send('mail.remainder-mail', $data, function ($message) use ($data) {
////                        $message->to($data['email'], config('app.name'))
////                            ->cc(['arjun@nordicsolutions.in'])
////                            ->subject('Reminder Mail');
////                    });
////                }
////            }
////            elseif ($compliantExpiredDate && Carbon::now()->startOfDay()->eq(Carbon::parse($compliantExpiredDate)->subWeeks(1)->startOfDay())) {
////                $users = User::role('country_head')->whereJsonContains('country_id', (string)$compliantSubMenu->country_id)->get();
////                foreach ($users as $user) {
////                    $data = [
////                        'subject' => 'Reminder Email',
////                        'email' => $user->email,
////                        'user' => $user,
////                        'complaint' => $compliantSubMenu,
////                    ];
////
////                    Mail::send('mail.remainder-mail', $data, function ($message) use ($data) {
////                        $message->to($data['email'], config('app.name'))
////                            ->cc(['arjun@nordicsolutions.in'])
////                            ->subject('Reminder Mail');
////                    });
////                }
////            }
////            elseif ($compliantExpiredDate && Carbon::now()->startOfDay()->eq(Carbon::parse($compliantExpiredDate)->subDay()->startOfDay())) {
////                $users = User::role('country_head')->whereJsonContains('country_id', (string)$compliantSubMenu->country_id)->get();
////                foreach ($users as $user) {
////                    $data = [
////                        'subject' => 'Reminder Email',
////                        'email' => $user->email,
////                        'user' => $user,
////                        'complaint' => $compliantSubMenu,
////                    ];
////
////                    Mail::send('mail.remainder-mail', $data, function ($message) use ($data) {
////                        $message->to($data['email'], config('app.name'))
////                            ->cc(['arjun@nordicsolutions.in'])
////                            ->subject('Reminder Mail');
////                    });
////                }
////            }
////            elseif ($compliantExpiredDate < Carbon::now() && $compliantExpiredDate != null) {
////                $compliantMenu = ComplianceSubMenu::find($compliantSubMenu->id)->update([
////                    'expired_date' => Carbon::parse($compliantExpiredDate)->addYear(),
////                ]);
////                $users = User::role('executive')->get();
////                foreach ($users as $user) {
////                    $data = [
////                        'subject' => 'Reminder Email',
////                        'email' => $user->email,
////                        'user' => $user,
////                        'complaint' => $compliantSubMenu,
////                    ];
////
////                    Mail::send('mail.remainder-mail', $data, function ($message) use ($data) {
////                        $message->to($data['email'], config('app.name'))
////                            ->cc(['arjun@nordicsolutions.in'])
////                            ->subject('Reminder Mail');
////                    });
////                }
////            }
//
//        }
//    }
    }
}
