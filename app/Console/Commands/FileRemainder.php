<?php

namespace App\Console\Commands;

use App\Models\ComplianceSubMenu;
use App\Models\CompliantMenuDetail;
use App\Models\Country;
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

        $compliantSubMenus = ComplianceSubMenu::whereNotNull('expired_date')

            ->groupBy('country_id')->unique('expired_date')->get();



        foreach ($compliantSubMenus as $countryId => $subMenus) {
            $users = User::role('country_head')
                ->whereJsonContains('country_id', (string)$countryId)
                ->get();
            $countryName = Country::find($countryId);
            $userNames = $users->pluck('name')->implode(', ');
            $emails = $users->pluck('email')->toArray();


                $data = [
                    'subject' => 'Compliance Reminder Email',
                    'complianceList' => $subMenus,
                    'countryId' => $countryId,
                    'countryName' => $countryName,
                    'userNames' => $userNames,
                ];

                Mail::send('mail.remainder-mail', $data, function ($message) use ($data,$emails) {
                    $message->to($emails, config('app.name'))
                        ->cc(['harish@nordicsolutions.in'])
                        ->subject($data['subject']);
                });
        }

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
