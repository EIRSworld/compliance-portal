<?php

namespace App\Exports;

use App\Models\CompliancePrimarySubMenu;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DashboardAddHocOperation implements FromView, ShouldAutoSize
{
    protected $calendar_year_id,$country_id,$entity_id;
    function __construct($calendar_year_id,$country_id,$entity_id) {
        $this->calendar_year_id = $calendar_year_id;
        $this->country_id = $country_id;
        $this->entity_id = $entity_id;
    }

    public function view(): View
    {
        $events_addhocs_operations = CompliancePrimarySubMenu::query()
            ->where('event_type', 'Add-Hoc')
            ->whereRelation('complianceSubMenu', 'sub_menu_name', 'Operations')
            ->where('calendar_year_id', $this->calendar_year_id);

        if ($this->country_id != 0) {
            $events_addhocs_operations = $events_addhocs_operations->where('country_id', $this->country_id);
        }
        if ($this->entity_id != 0) {
            $events_addhocs_operations = $events_addhocs_operations->where('entity_id', $this->entity_id);
        }

        $events_addhocs_operations = $events_addhocs_operations->get();



        return view('exports.dashboard-add-hoc-operation', [
            'events_addhocs_operations' => $events_addhocs_operations,
        ]);
    }
}
