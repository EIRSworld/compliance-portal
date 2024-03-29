<?php

namespace App\Exports;

use App\Models\Country;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DashboardSummaryExport implements FromView, ShouldAutoSize
{
    protected $calendar_year_id;
    function __construct($calendar_year_id) {
        $this->calendar_year_id = $calendar_year_id;
    }

    public function view(): View
    {
        $country = Country::get();
        $user = Auth::user();

        if ($user->hasAnyRole(['Country Head', 'Cluster Head', 'Compliance Finance Manager', 'Compliance Principle Manager', 'Compliance Finance Officer', 'Compliance Principle Officer'])) {
            $countryId = $user->country_id;
//            dd($countryId);
            if ($countryId !== null) {
                $countries = Country::whereIn('id', $countryId)->get();
            } else {
                // Set countries to an empty array if country_id is null
                $countries = [];
            }
        } else {
            $countries = $country;
        }
//
//        $countries = Country::get();
        return view('exports.dashboard-summary-export', [
            'calendar_year_id' => $this->calendar_year_id,
            'countries' => $countries,
        ]);
    }
}
