<?php

namespace App\Console\Commands;

use App\Exports\DashboardSummaryExport;
use App\Models\CalendarYear;
use App\Models\ComplianceSubMenu;
use App\Models\Country;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class SummaryReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:summary-report';

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

        $today = \Carbon\Carbon::now();
        $year = \Carbon\Carbon::now()->year;
        $calendarYear = CalendarYear::whereName($year)->first();
        $firstMonday = new \Carbon\Carbon('first monday of this month');

        if ($today->isSameDay($firstMonday)) {
        $user = User::role('Management')->first();

        $file_name = 'Dashboard Summary Report.xlsx';
        $path = 'reports/' . $file_name;
        Excel::store(new DashboardSummaryExport($calendarYear->id), $path);
        $storagePath = storage_path('app/' . $path);
        $data = [
            'subject' => 'Compliance Dashboard',
            'user' => $user->email,
            'name' => $user->name,
            'attachment' => $storagePath,
        ];

        Mail::send('mail.summary-report', $data, function ($message) use ($data, $storagePath) {
            $message->to($data['user'], config('app.name'))
                ->cc(['harish@nordicsolutions.in'])
                ->subject($data['subject'])
                ->attach($data['attachment']);
        });
        }
    }
}
