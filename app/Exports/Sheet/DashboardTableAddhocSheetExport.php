<?php

namespace App\Exports\Sheet;

use App\Models\ComplianceMenu;
use App\Models\CompliancePrimarySubMenu;
use App\Models\ComplianceSubMenu;
use App\Models\Country;
use App\Models\Entity;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class DashboardTableAddhocSheetExport implements FromView, WithTitle
{
    public $country, $entity, $type, $type_id, $compliance_sub_menu_id, $red;
    protected $calendar_year_id, $country_id, $entity_id, $regular_id, $regular_yearly,$compliance_menu,$compliance_menu_id;

    public function __construct($calendar_year_id, $country_id, $entity_id, $compliance_menu_id,$red)
    {
//        dd($calendar_year_id, $country_id, $entity_id, $compliance_menu_id,$red);
        $this->calendar_year_id = $calendar_year_id;
        $this->country_id = $country_id;
        $this->country = Country::whereId($this->country_id)->first();
        $this->entity_id = $entity_id;
        $this->entity = Entity::whereId($this->entity_id)->first();
//        $this->compliance_sub_menu_id = $compliance_sub_menu_id;
//        $this->compliance_sub_menu = ComplianceSubMenu::whereId($this->compliance_sub_menu_id)->first();
        $this->compliance_menu_id = $compliance_menu_id;
        $this->compliance_menu = ComplianceMenu::whereId($this->compliance_menu_id)->first();

        $this->red = $red;

    }

    public function title(): string
    {
        return ' ADD-HOC ' . '-' . $this->country->name . '-' . $this->entity->entity_name  ;
    }

    public function view(): View
    {
        if (auth()->user()->hasRole('Compliance Officer') || auth()->user()->hasRole('Cluster Head') || auth()->user()->hasRole('Country Head')) {
            $addhoc_events = CompliancePrimarySubMenu::whereCalendarYearId($this->calendar_year_id)
                ->whereCountryId($this->country_id)
                ->whereEntityId($this->entity_id)
                ->whereComplianceMenuId($this->compliance_menu_id)
//                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Add-Hoc')
                ->when($this->red, function($query, $red) {
                    if ($red == 'true') {
                        return $query->whereStatus('Red');
                    }
                    return $query;
                })
                ->orderBy('event_name', 'asc')
                ->whereAssignId(auth()->user()->id)
                ->get()->groupBy('event_name');
        } else {
            $addhoc_events = CompliancePrimarySubMenu::whereCalendarYearId($this->calendar_year_id)
                ->whereCountryId($this->country_id)
                ->whereEntityId($this->entity_id)
                ->whereComplianceMenuId($this->compliance_menu_id)
//                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Add-Hoc')
                ->when($this->red, function($query, $red) {
                    if ($red == 'true') {
                        return $query->whereStatus('Red');
                    }
                    return $query;
                })
                ->orderBy('event_name', 'asc')
//                ->whereAssignId(auth()->user()->id)
                ->get()->groupBy('event_name');
        }

        if (auth()->user()->hasRole('Compliance Officer') || auth()->user()->hasRole('Cluster Head') || auth()->user()->hasRole('Country Head')) {
            $event_name_yearly_addhoc = CompliancePrimarySubMenu::whereCalendarYearId($this->calendar_year_id)
                ->whereCountryId($this->country_id)
                ->whereEntityId($this->entity_id)
                ->whereComplianceMenuId($this->compliance_menu_id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Add-Hoc')
                ->when($this->red, function($query, $red) {
                    if ($red == 'true') {
                        return $query->whereStatus('Red');
                    }
                    return $query;
                })
                ->orderBy('event_name', 'asc')
                ->whereAssignId(auth()->user()->id)
                ->get();
        } else {
            $event_name_yearly_addhoc = CompliancePrimarySubMenu::whereCalendarYearId($this->calendar_year_id)
                ->whereCountryId($this->country_id)
                ->whereEntityId($this->entity_id)
                ->whereComplianceMenuId($this->compliance_menu_id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Add-Hoc')
                ->when($this->red, function($query, $red) {
                    if ($red == 'true') {
                        return $query->whereStatus('Red');
                    }
                    return $query;
                })
                ->orderBy('event_name', 'asc')
//                ->whereAssignId(auth()->user()->id)
                ->get();
        }
        if (auth()->user()->hasRole('Compliance Officer') || auth()->user()->hasRole('Cluster Head') || auth()->user()->hasRole('Country Head')) {
            $event_name_qtr_addhoc = CompliancePrimarySubMenu::whereCalendarYearId($this->calendar_year_id)
                ->whereCountryId($this->country_id)
                ->whereEntityId($this->entity_id)
                ->whereComplianceMenuId($this->compliance_menu_id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Add-Hoc')
                ->when($this->red, function($query, $red) {
                    if ($red == 'true') {
                        return $query->whereStatus('Red');
                    }
                    return $query;
                })
                ->orderBy('event_name', 'asc')
                ->whereAssignId(auth()->user()->id)
                ->get()->groupBy('event_name');
        } else {
            $event_name_qtr_addhoc = CompliancePrimarySubMenu::whereCalendarYearId($this->calendar_year_id)
                ->whereCountryId($this->country_id)
                ->whereEntityId($this->entity_id)
                ->whereComplianceMenuId($this->compliance_menu_id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Add-Hoc')
                ->when($this->red, function($query, $red) {
                    if ($red == 'true') {
                        return $query->whereStatus('Red');
                    }
                    return $query;
                })
                ->orderBy('event_name', 'asc')
//                ->whereAssignId(auth()->user()->id)
                ->get()->groupBy('event_name');
        }

        if (auth()->user()->hasRole('Compliance Officer') || auth()->user()->hasRole('Cluster Head') || auth()->user()->hasRole('Country Head')) {
            $event_name_monthly_addhoc = CompliancePrimarySubMenu::whereCalendarYearId($this->calendar_year_id)
                ->whereCountryId($this->country_id)
                ->whereEntityId($this->entity_id)
                ->whereComplianceMenuId($this->compliance_menu_id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Add-Hoc')
                ->when($this->red, function($query, $red) {
                    if ($red == 'true') {
                        return $query->whereStatus('Red');
                    }
                    return $query;
                })
                ->orderBy('event_name', 'asc')
                ->whereAssignId(auth()->user()->id)
                ->get()->groupBy('event_name');
        } else {
            $event_name_monthly_addhoc = CompliancePrimarySubMenu::whereCalendarYearId($this->calendar_year_id)
                ->whereCountryId($this->country_id)
                ->whereEntityId($this->entity_id)
                ->whereComplianceMenuId($this->compliance_menu_id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Add-Hoc')
                ->when($this->red, function($query, $red) {
                    if ($red == 'true') {
                        return $query->whereStatus('Red');
                    }
                    return $query;
                })
                ->orderBy('event_name', 'asc')
//                ->whereAssignId(auth()->user()->id)
                ->get()->groupBy('event_name');
        }

//dd($event_name_qtr_addhoc);

        return view('exports.sheet.dashboard-table-addhoc-sheet-export', [
            'event_name_yearly_addhoc' => $event_name_yearly_addhoc,
            'event_name_qtr_addhoc' => $event_name_qtr_addhoc,
            'event_name_monthly_addhoc' => $event_name_monthly_addhoc,
            'addhoc_events' => $addhoc_events,
            'country' => $this->country,
            'entity' => $this->entity,
//            'compliance_sub_menu' => $this->compliance_sub_menu,
        ]);
    }
}
