<?php

use App\Exports\DashboardSummaryExport;
use App\Models\Country;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

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

Route::get('/report', function () {
    $countries = Country::get();
//    dd($countries);
    return view('test',['countries' => $countries]);
});

Route::get('dashboard-summary/{id}', function ($calendar_year_id) {
    return Excel::download(new DashboardSummaryExport($calendar_year_id), 'Dashboard Summary Report.xlsx');
})->name('report.dashboard-summary');

Route::get('/', function () {
    return redirect('admin');
})->name('login');


Route::get('/document/delete/{id}',function ($id){

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

    $currentDate = \Carbon\Carbon::now()->format('Y-m-d');
    $currentYear = \Carbon\Carbon::now()->format('Y');
    $calendarYear = \App\Models\CalendarYear::whereName($currentYear)->first();
//    dd($currentYear,$calendarYear);
    $complianceSubMenus = \App\Models\ComplianceSubMenu::whereNotNull('expired_date')->orderBy('expired_date')->whereCalendarYearId($calendarYear->id)->get();
//    dd($complianceSubMenus);Country id add in this table
    $groupedByCountryIds = $complianceSubMenus->groupBy('country_id');
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
        $expiredDate =  \Carbon\Carbon::parse($complianceSubMenu->expired_date);
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


        if($expiredDate->between($sixthWeekStart,$sixthWeekEnd) && (!($complianceSubMenu->is_uploaded))){
            $sixthWeekIds[] = $complianceSubMenu->id;
            $combinedIds[] = $complianceSubMenu->id;
        }elseif($expiredDate->between($fifthWeekStart,$fifthWeekEnd) && (!($complianceSubMenu->is_uploaded))){
            $fifthWeekIds[] = $complianceSubMenu->id;
            $combinedIds[] = $complianceSubMenu->id;
        }elseif($expiredDate->between($fourthWeekStart,$fourthWeekEnd) && (!($complianceSubMenu->is_uploaded))){
            $fourthWeekIds[] = $complianceSubMenu->id;
            $combinedIds[] = $complianceSubMenu->id;
        }elseif($expiredDate->between($thirdWeekStart,$thirdWeekEnd) && (!($complianceSubMenu->is_uploaded))){
            $thirdWeekIds[] = $complianceSubMenu->id;
            $combinedIds[] = $complianceSubMenu->id;
        }elseif($expiredDate->between($secondWeekStart,$secondWeekEnd) && (!($complianceSubMenu->is_uploaded))){
            $secondWeekIds[] = $complianceSubMenu->id;
            $combinedIds[] = $complianceSubMenu->id;
        }elseif($expiredDate->between($firstWeekStart,$firstWeekEnd) && (!($complianceSubMenu->is_uploaded))){
            $firstWeekIds[] = $complianceSubMenu->id;
            $combinedIds[] = $complianceSubMenu->id;
        }elseif((\Carbon\Carbon::parse($expiredDate)->subday(1)->format('d-m-Y') === \Carbon\Carbon::now()->format('d-m-Y')) && (!($complianceSubMenu->is_uploaded)) && (!($complianceSubMenu->is_uploaded))){
            $beforeDateIds[] = $complianceSubMenu->id;
            $combinedIds[] = $complianceSubMenu->id;
        }
//        dd($combinedIds,$sixthWeekIds,$fifthWeekIds);
    }
//    dd(count($fifthWeekIds));
//dd(count(array($fourthWeekIds)) > 0);

    // if count greater than 0 isSixthWeek equal to 1 this is for the color
    if(count($sixthWeekIds) > 0){
        $sixthWeekDate = \Carbon\Carbon::parse($sixthWeekStart)->format('Y-m-d');
//        dd($sixthWeekDate);
        $isSixthWeek = 1;
    }
    if(count($fifthWeekIds) > 0){
        $fifthWeekDate = \Carbon\Carbon::parse($fifthWeekStart)->format('Y-m-d');
        $isFifthWeek = 1;
    }
    if(count($fourthWeekIds) > 0){
        $fourthWeekDate = \Carbon\Carbon::parse($fourthWeekStart)->format('Y-m-d');
        $isFourthWeek = 1;
    }
    if(count($thirdWeekIds) > 0){
        $thirdWeekDate = \Carbon\Carbon::parse($thirdWeekStart)->format('Y-m-d');
        $isThirdWeek = 1;
    }
    if(count($secondWeekIds) > 0){
        $secondWeekDate = \Carbon\Carbon::parse($secondWeekStart)->format('Y-m-d');
        $isSecondWeek = 1;
    }
    if(count($firstWeekIds) > 0){
        $firstWeekDate = \Carbon\Carbon::parse($firstWeekStart)->format('Y-m-d');
        $isFirstWeek = 1;
    }
    if(count($beforeDateIds) > 0){
        $beforeDate = \Carbon\Carbon::parse($expiredDate)->subday(1)->format('d-m-Y');
//        dd($sixthWeekDate);
        $isBeforeDay = 1;
    }
//dd($isFifthWeek);

    // Mail sending date (monday)
    if($isSixthWeek){
        $mailSendDate = $sixthWeekDate;
    }elseif($isFifthWeek){
        $mailSendDate = $fifthWeekDate;
    }elseif($isFourthWeek){
        $mailSendDate = $fourthWeekDate;
    }elseif($isThirdWeek){
        $mailSendDate = $thirdWeekDate;
    }elseif($isSecondWeek){
        $mailSendDate = $secondWeekDate;
    }elseif($isFirstWeek){
        $mailSendDate = $firstWeekDate;
    }elseif($isBeforeDay){
        $mailSendDate = $beforeDate;
    }
//    dd(($isBeforeDay));
//dd(strtotime($mailSendDate),strtotime($currentDate));
    if($mailSendDate &&  strtotime($mailSendDate) == strtotime($currentDate)) {
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
