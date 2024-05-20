<?php

namespace App\Exports;

use App\Models\ComplianceEvent;
use App\Models\CompliancePrimarySubMenu;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DashboardRegularOperation implements FromView, ShouldAutoSize
{
    protected $calendar_year_id,$country_id,$entity_id;
    function __construct($calendar_year_id,$country_id,$entity_id) {
        $this->calendar_year_id = $calendar_year_id;
        $this->country_id = $country_id;
        $this->entity_id = $entity_id;
    }

    public function view(): View
{
    $events_regulars_operations = CompliancePrimarySubMenu::query()
        ->where('event_type', 'Regular')
        ->whereRelation('complianceSubMenu', 'sub_menu_name', 'Operations')
        ->where('calendar_year_id', $this->calendar_year_id);

    if ($this->country_id != 0) {
        $events_regulars_operations = $events_regulars_operations->where('country_id', $this->country_id);
    }
    if ($this->entity_id != 0) {
        $events_regulars_operations = $events_regulars_operations->where('entity_id', $this->entity_id);
    }

    $events_regulars_operations = $events_regulars_operations->get();
//    dd($events_regulars_operations);

    return view('exports.dashboard-regular-operation', [
        'events_regulars_operations' => $events_regulars_operations,
    ]);
}
}
