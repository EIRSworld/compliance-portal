<?php

namespace App\Exports;

use App\Models\Country;
use Illuminate\Contracts\View\View;
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

        $countries = Country::get();
        return view('exports.dashboard-summary-export', [
            'calendar_year_id' => $this->calendar_year_id,
            'countries' => $countries,
        ]);
    }
}
