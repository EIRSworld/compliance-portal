<?php

use App\Exports\DashboardSummaryExport;
use App\Exports\EventExport;
use App\Models\CalendarYear;
use App\Models\Country;
use App\Models\User;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/test-email', function () {

//    $countries = [
//        'Tanzania' => [
//            'name' => ['Snehal Sheth', 'Sumantra'],
//            'to' => ['snehal.sheth@eirsworld.com', 'sumantra.banerjee@etgworld.com'],
//            'cc' => ['bhagyashri.solanki@eirsworld.com', 'joveil.canete@etgworld.com', 'pranita.jain@etgworld.com', 'abhishek.jain@etgworld.com', 'nathan.govender@etgworld.com', 'gyana.sanbad@etgworld.com']
//        ],
//        'IVC' => [
//            'name' => ['Alka Verma', 'Sumantra', 'Nolwenn', "Kouakou N'Goran Herve"],
//            'to' => ['alka.verma@eirsworld.com', 'sumantra.banerjee@etgworld.com', 'nolwenn.allano@etgworld.com', 'herve.kouakou@eirsworld.com'],
//            'cc' => ['deepak.goyal@etgworld.com', 'joveil.canete@etgworld.com', 'pranita.jain@etgworld.com', 'abhishek.jain@etgworld.com', 'nathan.govender@etgworld.com', 'gyana.sanbad@etgworld.com', 'mahesh.verulkar@etgworld.com']
////            'cc' => ['harrish.gunasekaran@eirsworld.com','harish@nordicsolutions.in']
//        ],
//
//        'Kenya' => [
//            'name' => ['Christine Gikunda', 'Gerald Yiminyi', 'Nigel Pillay', 'Nolwenn'],
//            'to' => ['christine.gikunda@etgworld.com', 'gerald.yiminyi@etgworld.com', 'nigel.pillay@etgworld.com', 'nolwenn.allano@etgworld.com'],
//            'cc' => ['etglke.accounts@etgworld.com', 'sunil.pulavarthi@etgworld.com', 'joveil.canete@etgworld.com', 'pranita.jain@etgworld.com', 'abhishek.jain@etgworld.com', 'nathan.govender@etgworld.com', 'gyana.sanbad@etgworld.com']
//        ],
//
//        'South Africa' => [
//            'name' => ['Nigel Pillay', 'Clinton Brown'],
//            'to' => ['nigel.pillay@etgworld.com', 'clinton.brown@etgworld.com'],
//            'cc' => ['preethi.s@etgworld.com', 'rakhee.bhowan@etgworld.com', 'joveil.canete@etgworld.com', 'pranita.jain@etgworld.com', 'abhishek.jain@etgworld.com', 'nathan.govender@etgworld.com', 'gyana.sanbad@etgworld.com']
//        ],
//        'Zambia' => [
//            'name' => ['Issac', 'Snehal Sheth', 'Margaret Banda'],
//            'to' => ['isaac.mkandawire@eirsworld.com', 'snehal.sheth@eirsworld.com', 'margaret.banda@eirsworld.com'],
//            'cc' => ['faiz.ahmad@etgworld.com', 'joveil.canete@etgworld.com', 'pranita.jain@etgworld.com', 'abhishek.jain@etgworld.com', 'nathan.govender@etgworld.com', 'gyana.sanbad@etgworld.com']
//        ],
//        'Mozambique' => [
//            'name' => ['Rakhee Bhowan'],
//            'to' => ['rakhee.bhowan@etgworld.com'],
//            'cc' => ['preethi.s@etgworld.com', 'joveil.canete@etgworld.com', 'pranita.jain@etgworld.com', 'abhishek.jain@etgworld.com', 'nathan.govender@etgworld.com', 'gyana.sanbad@etgworld.com']
//        ],
//        'Mauritius' => [
//            'name' => ['Sumantra'],
//            'to' => ['sumantra.banerjee@etgworld.com'],
//            'cc' => ['vindhyashree.kn@etgworld.com', 'gyana.sanbad@etgworld.com', 'joveil.canete@etgworld.com', 'pranita.jain@etgworld.com', 'abhishek.jain@etgworld.com', 'nathan.govender@etgworld.com']
//        ]
//    ];
    $countries = [
        'Tanzania' => [
            'name' => ['Pranita'],
            'to' => [ 'manoj.pachamuthu@eirsworld.com'],
            'cc' => ['harrish.gunasekaran@eirsworld.com']
        ],
        'IVC' => [
            'name' => ['Pranita'],
            'to' => ['manoj.pachamuthu@eirsworld.com'],
            'cc' => ['harrish.gunasekaran@eirsworld.com']
        ],

        'Kenya' => [
            'name' => ['Pranita'],
            'to' => ['manoj.pachamuthu@eirsworld.com'],
            'cc' => ['harrish.gunasekaran@eirsworld.com']
        ],

        'South Africa' => [
            'name' => ['Pranita'],
            'to' => ['manoj.pachamuthu@eirsworld.com'],
            'cc' => ['harrish.gunasekaran@eirsworld.com']
        ],
        'Zambia' => [
            'name' => ['Pranita'],
            'to' => ['manoj.pachamuthu@eirsworld.com'],
            'cc' => ['harrish.gunasekaran@eirsworld.com']
        ],
        'Mozambique' => [
            'name' => ['Pranita'],
            'to' => ['manoj.pachamuthu@eirsworld.com'],
            'cc' => ['harrish.gunasekaran@eirsworld.com']
        ],
        'Mauritius' => [
            'name' => ['Pranita'],
            'to' => ['manoj.pachamuthu@eirsworld.com'],
            'cc' => ['harrish.gunasekaran@eirsworld.com']
        ],
        'Malawi' => [
            'name' => ['Pranita'],
            'to' => ['manoj.pachamuthu@eirsworld.com'],
            'cc' => ['harrish.gunasekaran@eirsworld.com']
        ],
    ];

    foreach ($countries as $key => $country) {
        $countryModel = Country::whereName($key)->first();

        if ($countryModel) {
            Mail::to($country['to'])->cc($country['cc'])->send(new \App\Mail\DashboardEmail($countryModel->id, $country['name']));

            \Illuminate\Support\Facades\Log::info('Email sent successfully.', [
                'to' => $country['to'],
                'cc' => $country['cc'] ?? 'No CC',
                'country' => $country['name'],
            ]);
        }
    }
    dd('done');

});

Route::get('/red', function () {

    $now = Carbon::now();
    $currentYear = $now->year;
    if ($now->format('Y-m-d') <= $currentYear . "-03-31") {
        $previousYear = $currentYear - 1;
        $financeYear = $previousYear . '-' . $currentYear;
    } else {
        $nextYear = $currentYear + 1;
        $financeYear = $currentYear . '-' . $nextYear;
    }
    $calendarYear = CalendarYear::find(2);

    $firstMonday = new \Carbon\Carbon('first monday of this month');
//dd($firstMonday);

    $now = Carbon::parse('2024-05-06');
    if ($now->isSameDay($firstMonday)) {
        $user = User::find(3);

        $file_name = 'Dashboard Summary Report.xlsx';
        $path = 'reports/' . $file_name;
        Excel::store(new DashboardSummaryExport($calendarYear->id), $path);
        $storagePath = storage_path('app/' . $path);
        $data = [
            'subject' => 'Compliance Dashboard',
            'user' => ['harrish.gunasekaran@eirsworld.com'],
//            'name' => $user->name,
            'attachment' => $storagePath,
        ];

        Mail::send('mail.summary-report', $data, function ($message) use ($data, $storagePath) {
            $message->to($data['user'], config('app.name'))
//                ->cc(['harish@nordicsolutions.in'])
                ->subject($data['subject'])
                ->attach($data['attachment']);
        });
    }


//    return back()->with('error', 'Document not found.');
})->name('red');


//New Dashboard
Route::get('new-dashboard-export/{id}/{country_id}/{entity_id}/{red?}', function ($calendar_year_id, $country_id, $entity_id, $red) {
    return Excel::download(new \App\Exports\NewDashboardExport($calendar_year_id, $country_id, $entity_id, $red), 'Dashboard Report.xlsx');
})->name('report.new-dashboard-export');

Route::get('/report', function () {
    $countries = Country::get();
//    dd($countries);
    return view('test', ['countries' => $countries]);
});

Route::get('dashboard-summary/{id}', function ($calendar_year_id) {
    return Excel::download(new DashboardSummaryExport($calendar_year_id), 'Dashboard Summary Report.xlsx');
})->name('report.dashboard-summary');

Route::get('dashboard-event-summary/{id}', function ($calendar_year_id) {
    return Excel::download(new \App\Exports\DashboardEventSummaryExport($calendar_year_id), 'Dashboard Event Summary Report.xlsx');
})->name('report.dashboard-event-summary');

//Regular
Route::get('dashboard-regular-operations/{id}/{country_id}/{entity_id}', function ($calendar_year_id, $country_id, $entity_id) {
    return Excel::download(new \App\Exports\DashboardRegularOperation($calendar_year_id, $country_id, $entity_id), 'Dashboard Regular Operations Report.xlsx');
})->name('report.dashboard-regular-operations');

Route::get('dashboard-regular-finance/{id}/{country_id}/{entity_id}', function ($calendar_year_id, $country_id, $entity_id) {
    return Excel::download(new \App\Exports\DashboardRegularFinance($calendar_year_id, $country_id, $entity_id), 'Dashboard Regular Finance Report.xlsx');
})->name('report.dashboard-regular-finance');

Route::get('dashboard-regular-hr/{id}/{country_id}/{entity_id}', function ($calendar_year_id, $country_id, $entity_id) {
    return Excel::download(new \App\Exports\DashboardRegularHr($calendar_year_id, $country_id, $entity_id), 'Dashboard Regular Hr Report.xlsx');
})->name('report.dashboard-regular-hr');

//Add-Hoc
Route::get('dashboard-add-hoc-operations/{id}/{country_id}/{entity_id}', function ($calendar_year_id, $country_id, $entity_id) {
    return Excel::download(new \App\Exports\DashboardAddHocOperation($calendar_year_id, $country_id, $entity_id), 'Dashboard Add-Hoc Operations Report.xlsx');
})->name('report.dashboard-add-hoc-operations');

Route::get('dashboard-add-hoc-finance/{id}/{country_id}/{entity_id}', function ($calendar_year_id, $country_id, $entity_id) {
    return Excel::download(new \App\Exports\DashboardAddHocFinance($calendar_year_id, $country_id, $entity_id), 'Dashboard Add-Hoc Finance Report.xlsx');
})->name('report.dashboard-add-hoc-finance');

Route::get('dashboard-add-hoc-hr/{id}/{country_id}/{entity_id}', function ($calendar_year_id, $country_id, $entity_id) {
    return Excel::download(new \App\Exports\DashboardAddHocHr($calendar_year_id, $country_id, $entity_id), 'Dashboard Add-Hoc Hr Report.xlsx');
})->name('report.dashboard-add-hoc-hr');

Route::get('/', function () {
    return redirect('admin');
})->name('login');


Route::get('/document/delete/{id}', function ($id) {

    $document = \Spatie\MediaLibrary\MediaCollections\Models\Media::find($id);

    if ($document) {
        // Delete the document
        $document->delete();
        Notification::make()
            ->title('Deleted Successfully')
            ->success()
            ->send();

        return back();
    }

//    return back()->with('error', 'Document not found.');
})->name('document.delete');


Route::get('/test', function () {

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


    $currentDate = \Carbon\Carbon::now()->format('Y-m-d');
    $currentYear = \Carbon\Carbon::now()->format('Y');
    $calendarYear = \App\Models\CalendarYear::whereName($currentYear)->first();
//    dd($currentYear,$calendarYear);
    $complianceSubMenus = \App\Models\ComplianceSubMenu::whereNotNull('expired_date')->orderBy('expired_date')->whereCalendarYearId($calendarYear->id)->get();
//    dd($complianceSubMenus);Country id add in this table
    $groupedByCountryIds = $complianceSubMenus->groupBy('country_id');
//    dd($groupedByCountryIds);
//    dd($groupedByCountryIds);

    $mailSendDate = '';

    $sixthWeekIds = [];
    $fifthWeekIds = [];
    $fourthWeekIds = [];
    $thirdWeekIds = [];
    $secondWeekIds = [];
    $firstWeekIds = [];
    $beforeDateIds = [];
    $combinedIds = [];

    $sixthWeekDate = '';
    $fifthWeekDate = '';
    $fourthWeekDate = '';
    $thirdWeekDate = '';
    $secondWeekDate = '';
    $firstWeekDate = '';
    $beforeDate = '';

    $isSixthWeek = 0;
    $isFifthWeek = 0;
    $isFourthWeek = 0;
    $isThirdWeek = 0;
    $isSecondWeek = 0;
    $isFirstWeek = 0;
    $isBeforeDay = 0;


    // Getting all the start and end date for 6 weeks
//    $sixthWeekStart = \Carbon\Carbon::now()->subDays(42);
    $sixthWeekStart = \Carbon\Carbon::now()->startOfWeek()->subWeek(6);

    $sixthWeekEnd = \Carbon\Carbon::now()->startOfWeek()->subWeek(5)->subSecond();
//    dd($sixthWeekStart,$sixthWeekEnd);
    $fifthWeekStart = \Carbon\Carbon::now()->startOfWeek()->subWeek(5);
    $fifthWeekEnd = \Carbon\Carbon::now()->startOfWeek()->subWeek(4)->subSecond();
    $fourthWeekStart = \Carbon\Carbon::now()->startOfWeek()->subWeek(4);
    $fourthWeekEnd = \Carbon\Carbon::now()->startOfWeek()->subWeek(3)->subSecond();
    $thirdWeekStart = \Carbon\Carbon::now()->startOfWeek()->subWeek(3);
    $thirdWeekEnd = \Carbon\Carbon::now()->startOfWeek()->subWeek(2)->subSecond();
    $secondWeekStart = \Carbon\Carbon::now()->startOfWeek()->subWeek(2);
    $secondWeekEnd = \Carbon\Carbon::now()->startOfWeek()->subWeek(1)->subSecond();
    $firstWeekStart = \Carbon\Carbon::now()->startOfWeek()->subWeek(1);
    $firstWeekEnd = \Carbon\Carbon::now()->startOfWeek()->subSecond();
//    dd($firstWeekStart,$firstWeekEnd);
//    dd($fifthWeekStart,$fifthWeekEnd);
//dd($complianceSubMenus);

    // Checking expired date in which week
    foreach ($complianceSubMenus as $complianceSubMenu) {
        $expiredDate = \Carbon\Carbon::parse($complianceSubMenu->expired_date);
//        dd($expiredDate);

//        dd((\Carbon\Carbon::parse($expiredDate)->subday(1)->format('d-m-Y') === \Carbon\Carbon::now()->format('d-m-Y')) && (!($complianceSubMenu->is_uploaded)));

//        dd((\Carbon\Carbon::parse($expiredDate)->subday() === \Carbon\Carbon::now()) && (!($complianceSubMenu->is_uploaded)));
//        dd($expiredDate->between($sixthWeekStart,$sixthWeekEnd) && (!($complianceSubMenu->is_uploaded)),
//            $expiredDate->between($firstWeekStart,$firstWeekEnd) && (!($complianceSubMenu->is_uploaded)),
//            $expiredDate->between($fifthWeekStart,$fifthWeekEnd) && (!($complianceSubMenu->is_uploaded)),
//            $expiredDate->between($fourthWeekStart,$fourthWeekEnd) && (!($complianceSubMenu->is_uploaded)),
//            $expiredDate->between($thirdWeekStart,$thirdWeekEnd) && (!($complianceSubMenu->is_uploaded)),
//            $expiredDate->between($secondWeekStart,$secondWeekEnd) && (!($complianceSubMenu->is_uploaded)),
//            \Carbon\Carbon::parse($expiredDate)->subday(1)->format('d-m-Y') === \Carbon\Carbon::now()->format('d-m-Y') && (!($complianceSubMenu->is_uploaded)) && (!($complianceSubMenu->is_uploaded)),
//        );


        if ($expiredDate->between($sixthWeekStart, $sixthWeekEnd) && (!($complianceSubMenu->is_uploaded))) {
            $sixthWeekIds[] = $complianceSubMenu->id;
            $combinedIds[] = $complianceSubMenu->id;
        } elseif ($expiredDate->between($fifthWeekStart, $fifthWeekEnd) && (!($complianceSubMenu->is_uploaded))) {
            $fifthWeekIds[] = $complianceSubMenu->id;
            $combinedIds[] = $complianceSubMenu->id;
        } elseif ($expiredDate->between($fourthWeekStart, $fourthWeekEnd) && (!($complianceSubMenu->is_uploaded))) {
            $fourthWeekIds[] = $complianceSubMenu->id;
            $combinedIds[] = $complianceSubMenu->id;
        } elseif ($expiredDate->between($thirdWeekStart, $thirdWeekEnd) && (!($complianceSubMenu->is_uploaded))) {
            $thirdWeekIds[] = $complianceSubMenu->id;
            $combinedIds[] = $complianceSubMenu->id;
        } elseif ($expiredDate->between($secondWeekStart, $secondWeekEnd) && (!($complianceSubMenu->is_uploaded))) {
            $secondWeekIds[] = $complianceSubMenu->id;
            $combinedIds[] = $complianceSubMenu->id;
        } elseif ($expiredDate->between($firstWeekStart, $firstWeekEnd) && (!($complianceSubMenu->is_uploaded))) {
            $firstWeekIds[] = $complianceSubMenu->id;
            $combinedIds[] = $complianceSubMenu->id;
        } elseif ((\Carbon\Carbon::parse($expiredDate)->subday(1)->format('d-m-Y') === \Carbon\Carbon::now()->format('d-m-Y')) && (!($complianceSubMenu->is_uploaded)) && (!($complianceSubMenu->is_uploaded))) {
            $beforeDateIds[] = $complianceSubMenu->id;
            $combinedIds[] = $complianceSubMenu->id;
        }
//        dd($combinedIds,$sixthWeekIds,$fifthWeekIds);
    }
//    dd(count($fifthWeekIds));
//dd(count(array($fourthWeekIds)) > 0);

    // if count greater than 0 isSixthWeek equal to 1 this is for the color
    if (count($sixthWeekIds) > 0) {
        $sixthWeekDate = \Carbon\Carbon::parse($sixthWeekStart)->format('Y-m-d');
//        dd($sixthWeekDate);
        $isSixthWeek = 1;
    }
    if (count($fifthWeekIds) > 0) {
        $fifthWeekDate = \Carbon\Carbon::parse($fifthWeekStart)->format('Y-m-d');
        $isFifthWeek = 1;
    }
    if (count($fourthWeekIds) > 0) {
        $fourthWeekDate = \Carbon\Carbon::parse($fourthWeekStart)->format('Y-m-d');
        $isFourthWeek = 1;
    }
    if (count($thirdWeekIds) > 0) {
        $thirdWeekDate = \Carbon\Carbon::parse($thirdWeekStart)->format('Y-m-d');
        $isThirdWeek = 1;
    }
    if (count($secondWeekIds) > 0) {
        $secondWeekDate = \Carbon\Carbon::parse($secondWeekStart)->format('Y-m-d');
        $isSecondWeek = 1;
    }
    if (count($firstWeekIds) > 0) {
        $firstWeekDate = \Carbon\Carbon::parse($firstWeekStart)->format('Y-m-d');
        $isFirstWeek = 1;
    }
    if (count($beforeDateIds) > 0) {
        $beforeDate = \Carbon\Carbon::parse($expiredDate)->subday(1)->format('d-m-Y');
//        dd($sixthWeekDate);
        $isBeforeDay = 1;
    }
//dd($isFifthWeek);

    // Mail sending date (monday)
    if ($isSixthWeek) {
        $mailSendDate = $sixthWeekDate;
    } elseif ($isFifthWeek) {
        $mailSendDate = $fifthWeekDate;
    } elseif ($isFourthWeek) {
        $mailSendDate = $fourthWeekDate;
    } elseif ($isThirdWeek) {
        $mailSendDate = $thirdWeekDate;
    } elseif ($isSecondWeek) {
        $mailSendDate = $secondWeekDate;
    } elseif ($isFirstWeek) {
        $mailSendDate = $firstWeekDate;
    } elseif ($isBeforeDay) {
        $mailSendDate = $beforeDate;
    }
//    dd(($isBeforeDay));
//dd(strtotime($mailSendDate),strtotime($currentDate));
    if ($mailSendDate && strtotime($mailSendDate) == strtotime($currentDate)) {
//        dd($mailSendDate);
        foreach ($groupedByCountryIds as $key => $groupedByCountryId) {
            //    dd($key,$groupedByCountryId);

            $users = \App\Models\User::role('country_head')
                ->whereJsonContains('country_id', (string)$key)
                ->get();

            $countryName = Country::find($key);
            $userNames = $users->pluck('name')->implode(', ');
            $emails = $users->pluck('email')->toArray();

//            dd($isSixthWeek,$isFifthWeek,$isFourthWeek,$isThirdWeek,$isSecondWeek,$isFirstWeek,$isBeforeDay);
            $data = [
                'subject' => 'Compliance Reminder Email',
                'complianceList' => $groupedByCountryId,
                'countryId' => $key,
                'countryName' => $countryName,
                'userNames' => $userNames,
                'isSixthWeek' => $isSixthWeek,
                'isFifthWeek' => $isFifthWeek,
                'isFourthWeek' => $isFourthWeek,
                'isThirdWeek' => $isThirdWeek,
                'isSecondWeek' => $isSecondWeek,
                'isFirstWeek' => $isFirstWeek,
                'isBeforeDay' => $isBeforeDay,
                'sixthWeekDate' => $sixthWeekDate,
                'fifthWeekDate' => $fifthWeekDate,
                'fourthWeekDate' => $fourthWeekDate,
                'thirdWeekDate' => $thirdWeekDate,
                'secondWeekDate' => $secondWeekDate,
                'firstWeekDate' => $firstWeekDate,
                'beforeDate' => $beforeDate,
                'combinedIds' => $combinedIds,
            ];


            Mail::send('mail.remainder-mail', $data, function ($message) use ($data, $emails) {
                $message->to($emails, config('app.name'))
                    //                ->cc(['harish@nordicsolutions.in'])
                    ->subject($data['subject']);
            });
        }
    }


})->name('test');



Route::get('/red-status', function () {

    $compliancePrimarySubMenus = \App\Models\CompliancePrimarySubMenu::where('status','=','Amber')->where('is_uploaded','=',0)->get();
    foreach ($compliancePrimarySubMenus as $compliancePrimarySubMenu) {

        $current_date = \Carbon\Carbon::now()->format('Y-m-d');
        $due_date = \Carbon\Carbon::parse($compliancePrimarySubMenu->due_date)->format('Y-m-d');
        if ($current_date > $due_date) {
            $compliancePrimarySubMenu->update([
                'status' => 'Red',
            ]);
        }
    }
    dd('test');
});
