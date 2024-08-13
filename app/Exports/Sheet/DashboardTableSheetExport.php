<?php

namespace App\Exports\Sheet;

use App\Models\CompliancePrimarySubMenu;
use App\Models\ComplianceSubMenu;
use App\Models\Country;
use App\Models\Entity;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class DashboardTableSheetExport implements FromView, WithTitle, ShouldAutoSize
{
    public $country, $entity, $type, $type_id, $compliance_sub_menu_id, $red;
    protected $calendar_year_id, $country_id, $entity_id, $regular_id, $regular_yearly;

    public function __construct($calendar_year_id, $country_id, $entity_id, $compliance_sub_menu_id,$red)
    {
        $this->calendar_year_id = $calendar_year_id;
        $this->country_id = $country_id;
        $this->country = Country::whereId($this->country_id)->first();
        $this->entity_id = $entity_id;
        $this->entity = Entity::whereId($this->entity_id)->first();
        $this->compliance_sub_menu_id = $compliance_sub_menu_id;
        $this->compliance_sub_menu = ComplianceSubMenu::whereId($this->compliance_sub_menu_id)->first();

        $this->red = $red;

//        dd($this->red);
//        $this->regular_yearly = ComplianceSubMenu::whereCalendarYearId($this->calendar_year_id)->where('country_id', $this->country_id)
//            ->where('entity_id', $this->entity_id)
//            ->first();
//        $this->type = $type;
//        $this->type_id = $type_id;
//        $this->regular_id = $regular_id;

    }

//    public function title(): string
//    {
//        return $this->country->name . ' ' . $this->entity->entity_name . ' ' . $this->compliance_sub_menu->sub_menu_name;
//    }
    public function title(): string
    {
        return substr($this->compliance_sub_menu->sub_menu_name, 0, 1) . '-' . $this->country->name . '-' . $this->entity->entity_name  ;
    }

    public function view(): View
    {
        if (auth()->user()->hasRole('Compliance Officer') || auth()->user()->hasRole('Cluster Head') || auth()->user()->hasRole('Country Head')) {
            $event_name_yearly_regular = CompliancePrimarySubMenu::whereCalendarYearId($this->calendar_year_id)
                ->whereCountryId($this->country_id)
                ->whereEntityId($this->entity_id)
                ->whereComplianceSubMenuId($this->compliance_sub_menu_id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Regular')
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
            $event_name_yearly_regular = CompliancePrimarySubMenu::whereCalendarYearId($this->calendar_year_id)
                ->whereCountryId($this->country_id)
                ->whereEntityId($this->entity_id)
                ->whereComplianceSubMenuId($this->compliance_sub_menu_id)
                ->where('occurrence', '=', 'Yearly')
                ->where('event_type', '=', 'Regular')
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
            $event_name_qtr_regular = CompliancePrimarySubMenu::whereCalendarYearId($this->calendar_year_id)
                ->whereCountryId($this->country_id)
                ->whereEntityId($this->entity_id)
                ->whereComplianceSubMenuId($this->compliance_sub_menu_id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Regular')
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
            $event_name_qtr_regular = CompliancePrimarySubMenu::whereCalendarYearId($this->calendar_year_id)
                ->whereCountryId($this->country_id)
                ->whereEntityId($this->entity_id)
                ->whereComplianceSubMenuId($this->compliance_sub_menu_id)
                ->where('occurrence', '=', 'Qtr')
                ->where('event_type', '=', 'Regular')
                ->when($this->red, function($query, $red) {
                    if ($red == 'true') {
                        return $query->whereStatus('Red');
                    }
                    return $query;
                })
                ->orderBy('event_name', 'asc')
//            ->whereAssignId(auth()->user()->id)
                ->get()->groupBy('event_name');
        }


        if (auth()->user()->hasRole('Compliance Officer') || auth()->user()->hasRole('Cluster Head') || auth()->user()->hasRole('Country Head')) {
            $event_name_monthly_regular = CompliancePrimarySubMenu::whereCalendarYearId($this->calendar_year_id)
                ->whereCountryId($this->country_id)
                ->whereEntityId($this->entity_id)
                ->whereComplianceSubMenuId($this->compliance_sub_menu_id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Regular')
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
            $event_name_monthly_regular = CompliancePrimarySubMenu::whereCalendarYearId($this->calendar_year_id)
                ->whereCountryId($this->country_id)
                ->whereEntityId($this->entity_id)
                ->whereComplianceSubMenuId($this->compliance_sub_menu_id)
                ->where('occurrence', '=', 'Monthly')
                ->where('event_type', '=', 'Regular')
                ->when($this->red, function($query, $red) {
                    if ($red == 'true') {
                        return $query->whereStatus('Red');
                    }
                    return $query;
                })
                ->orderBy('event_name', 'asc')
//            ->whereAssignId(auth()->user()->id)
                ->get()->groupBy('event_name');
        }
        return view('exports.sheet.dashboard-table-sheet-export', [
            'event_name_yearly_regular' => $event_name_yearly_regular,
            'event_name_qtr_regular' => $event_name_qtr_regular,
            'event_name_monthly_regular' => $event_name_monthly_regular,
            'country' => $this->country,
            'entity' => $this->entity,
            'compliance_sub_menu' => $this->compliance_sub_menu,
        ]);
    }
}
