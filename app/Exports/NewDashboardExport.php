<?php

namespace App\Exports;

use App\Exports\Sheet\DashboardTableAddhocSheetExport;
use App\Exports\Sheet\DashboardTableSheetExport;
use App\Models\ComplianceMenu;
use App\Models\CompliancePrimarySubMenu;
use App\Models\ComplianceSubMenu;
use App\Models\Country;
use App\Models\Entity;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class NewDashboardExport implements WithMultipleSheets
{
    protected $calendar_year_id, $country_id, $entity_id, $regular_id, $red;

    function __construct($calendar_year_id, $country_id, $entity_id, $red)
    {
        $this->calendar_year_id = $calendar_year_id;
        $this->country_id = $country_id;
        $this->entity_id = $entity_id;
        $this->red = $red;


//        $this->regular_id = $regular_id;
//        dd($this->calendar_year_id,$this->country_id,$this->entity_id);
    }

    public function sheets(): array
    {
        $sheets = [];
        $countriesQuery = Country::query();

        if ($this->country_id) {
            $countries = $countriesQuery->whereId($this->country_id)->get();
        } elseif ($this->country_id == 0) {
            $countries = $countriesQuery->get();
        }

        foreach ($countries as $country) {
            $entitiesQuery = Entity::query();

            if ($this->entity_id) {
                $entities = $entitiesQuery->whereCountryId($country->id)->whereId($this->entity_id)->get();
            } elseif ($this->entity_id == 0) {
                $entities = $entitiesQuery->whereCountryId($country->id)->get();
            }

            foreach ($entities as $entity) {
                $complianceMenus = ComplianceMenu::whereCalendarYearId($this->calendar_year_id)
                    ->whereCountryId($country->id)
                    ->whereEntityId($entity->id)
                    ->get();

                foreach ($complianceMenus as $complianceMenu) {
                    // Add DashboardTableAddhocSheetExport
                    $sheets[] = new DashboardTableAddhocSheetExport($this->calendar_year_id, $country->id, $entity->id, $complianceMenu->id, $this->red);

                    if (auth()->user()->hasRole('Compliance Manager')) {
                        $user = User::find(auth()->user()->id);
                        $complianceSubMenus = ComplianceSubMenu::whereCalendarYearId($this->calendar_year_id)
                            ->whereCountryId($country->id)
                            ->whereEntityId($entity->id)
                            ->whereSubMenuName($user->compliance_type)
                            ->get();
                    } else {
                        $complianceSubMenus = ComplianceSubMenu::whereComplianceMenuId($complianceMenu->id)->get();
                    }

                    foreach ($complianceSubMenus as $complianceSubMenu) {
                        $sheets[] = new DashboardTableSheetExport($this->calendar_year_id, $country->id, $entity->id, $complianceSubMenu->id, $this->red);
                    }
                }
            }
        }

        return $sheets;
    }



}
