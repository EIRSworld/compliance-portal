<?php

namespace App\Console\Commands;

use App\Models\ComplianceSubMenu;
use App\Models\CompliantMenuDetail;
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

        $compliantMenuDetails = ComplianceSubMenu::get();

        foreach ($compliantMenuDetails as $compliantMenuDetail) {

            $compliantExpiredDate = $compliantMenuDetail->expired_date;

            if ($compliantExpiredDate < Carbon::now() && $compliantExpiredDate != null){
                $compliantMenu = ComplianceSubMenu::find($compliantMenuDetail->id)->update([
                    'expired_date' => Carbon::parse($compliantExpiredDate)->addYear(),
                ]);
            }
            if ($compliantExpiredDate && Carbon::now()->startOfDay()->eq(Carbon::parse($compliantExpiredDate)->subWeeks(6)->startOfDay())) {
                $users = User::role('country_head')->whereJsonContains('country_id', (string)$compliantMenuDetail->country_id)->get();
                foreach ($users as $user) {
                    $data = [
                        'subject' => 'Reminder Email',
                        'email' => $user->email,
                        'user' => $user,
                        'complaint' => $compliantMenuDetail,
                    ];

                    Mail::send('mail.remainder-mail', $data, function ($message) use ($data) {
                        $message->to($data['email'], config('app.name'))
                            ->subject('Reminder Mail');
                    });
                }
            }
            elseif ($compliantExpiredDate && Carbon::now()->startOfDay()->eq(Carbon::parse($compliantExpiredDate)->subWeeks(5)->startOfDay())) {
                $users = User::role('country_head')->whereJsonContains('country_id', (string)$compliantMenuDetail->country_id)->get();
                foreach ($users as $user) {
                    $data = [
                        'subject' => 'Reminder Email',
                        'email' => $user->email,
                        'user' => $user,
                        'complaint' => $compliantMenuDetail,
                    ];

                    Mail::send('mail.remainder-mail', $data, function ($message) use ($data) {
                        $message->to($data['email'], config('app.name'))
                            ->subject('Reminder Mail');
                    });
                }
            }
            elseif ($compliantExpiredDate && Carbon::now()->startOfDay()->eq(Carbon::parse($compliantExpiredDate)->subWeeks(4)->startOfDay())) {
                $users = User::role('country_head')->whereJsonContains('country_id', (string)$compliantMenuDetail->country_id)->get();
                foreach ($users as $user) {
                    $data = [
                        'subject' => 'Reminder Email',
                        'email' => $user->email,
                        'user' => $user,
                        'complaint' => $compliantMenuDetail,
                    ];

                    Mail::send('mail.remainder-mail', $data, function ($message) use ($data) {
                        $message->to($data['email'], config('app.name'))
                            ->subject('Reminder Mail');
                    });
                }
            }
            elseif ($compliantExpiredDate && Carbon::now()->startOfDay()->eq(Carbon::parse($compliantExpiredDate)->subWeeks(3)->startOfDay())) {
                $users = User::role('country_head')->whereJsonContains('country_id', (string)$compliantMenuDetail->country_id)->get();
                foreach ($users as $user) {
                    $data = [
                        'subject' => 'Reminder Email',
                        'email' => $user->email,
                        'user' => $user,
                        'complaint' => $compliantMenuDetail,
                    ];

                    Mail::send('mail.remainder-mail', $data, function ($message) use ($data) {
                        $message->to($data['email'], config('app.name'))
                            ->subject('Reminder Mail');
                    });
                }
            }
            elseif ($compliantExpiredDate && Carbon::now()->startOfDay()->eq(Carbon::parse($compliantExpiredDate)->subWeeks(2)->startOfDay())) {
                $users = User::role('country_head')->whereJsonContains('country_id', (string)$compliantMenuDetail->country_id)->get();
                foreach ($users as $user) {
                    $data = [
                        'subject' => 'Reminder Email',
                        'email' => $user->email,
                        'user' => $user,
                        'complaint' => $compliantMenuDetail,
                    ];

                    Mail::send('mail.remainder-mail', $data, function ($message) use ($data) {
                        $message->to($data['email'], config('app.name'))
                            ->subject('Reminder Mail');
                    });
                }
            }
            elseif ($compliantExpiredDate && Carbon::now()->startOfDay()->eq(Carbon::parse($compliantExpiredDate)->subWeeks(1)->startOfDay())) {
                $users = User::role('country_head')->whereJsonContains('country_id', (string)$compliantMenuDetail->country_id)->get();
                foreach ($users as $user) {
                    $data = [
                        'subject' => 'Reminder Email',
                        'email' => $user->email,
                        'user' => $user,
                        'complaint' => $compliantMenuDetail,
                    ];

                    Mail::send('mail.remainder-mail', $data, function ($message) use ($data) {
                        $message->to($data['email'], config('app.name'))
                            ->subject('Reminder Mail');
                    });
                }
            }
            elseif ($compliantExpiredDate && Carbon::now()->startOfDay()->eq(Carbon::parse($compliantExpiredDate)->subDay()->startOfDay())) {
                $users = User::role('country_head')->whereJsonContains('country_id', (string)$compliantMenuDetail->country_id)->get();
                foreach ($users as $user) {
                    $data = [
                        'subject' => 'Reminder Email',
                        'email' => $user->email,
                        'user' => $user,
                        'complaint' => $compliantMenuDetail,
                    ];

                    Mail::send('mail.remainder-mail', $data, function ($message) use ($data) {
                        $message->to($data['email'], config('app.name'))
                            ->subject('Reminder Mail');
                    });
                }
            }

        }
    }
}
