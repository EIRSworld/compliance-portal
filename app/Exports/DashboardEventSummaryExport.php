<?php

namespace App\Exports;

use App\Models\ComplianceEvent;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DashboardEventSummaryExport implements FromView, ShouldAutoSize
{
    protected $calendar_year_id;
    function __construct($calendar_year_id) {
        $this->calendar_year_id = $calendar_year_id;
    }

    public function view(): View
    {

        $complianceEvents = ComplianceEvent::where('calendar_year_id', $this->calendar_year_id)->get();
        $user = Auth::user();
        if ($user->hasAnyRole(['Country Head', 'Cluster Head', 'Compliance Finance Manager', 'Compliance Principle Manager', 'Compliance Finance Officer', 'Compliance Principle Officer'])) {
            $countryId = $user->country_id;
            if ($countryId !== null) {
                $events = ComplianceEvent::where('calendar_year_id', $this->calendar_year_id)->whereIn('country_id', $countryId)->get();
            } else {
                $events = [];
            }
        } else {
            $events = $complianceEvents;
        }
//        $events = ComplianceEvent::where('calendar_year_id',$this->calendar_year_id)->whereIn('country_id', $this->country_ids)->get();
        return view('exports.dashboard-event-summary', [
            'calendar_year_id' => $this->calendar_year_id,
            'events' => $events,
        ]);
    }
}
