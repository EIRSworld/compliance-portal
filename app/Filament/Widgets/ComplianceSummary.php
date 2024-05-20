<?php

namespace App\Filament\Widgets;

use App\Exports\DashboardSummaryExport;
use App\Models\CalendarYear;
use App\Models\ComplianceEvent;
use App\Models\CompliancePrimarySubMenu;
use App\Models\ComplianceSubMenu;
use App\Models\Country;
use App\Models\Document;
use App\Models\Entity;
use App\Models\User;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ComplianceSummary extends Widget implements HasForms
{

    use InteractsWithForms;

    protected static string $view = 'filament.widgets.compliance-summary';

    protected int|string|array $columnSpan = 2;
    protected static ?string $heading = 'Dashboard Summary';

    public $countries, $country_id,$entity_id, $calendar_year_id, $country, $compliance_events, $entities, $operations, $finances;
    public $events_regulars_operations, $events_regulars_finances, $events_regulars_hrs, $events_addhocs_operations, $events_addhocs_finances, $events_addhocs_hrs;

    public function mount()
    {
//        $now = Carbon::now();
//        $currentYear = $now->year;
//        if ($now->format('Y-m-d') <= $currentYear . "-03-31") {
//            $previousYear = $currentYear - 1;
//            $financeYear = $previousYear . '-' . $currentYear;
//        } else {
//            $nextYear = $currentYear + 1;
//            $financeYear = $currentYear . '-' . $nextYear;
//        }
////        $currentYear = Carbon::now()->year;
//        $this->calendar_year_id = CalendarYear::where('name', $financeYear)->value('id');
//        dd($this->calendar_year_id);

//        $this->country = Country::get();
//        $user = Auth::user();
//        if ($user->hasRole('Country Head')) {
//            $countryId = $user->country_id;
//            $this->countries = Country::whereIn('id', $countryId)->get();
//        } elseif ($user->hasRole('Cluster Head')) {
//            $countryId = $user->country_id;
//            $this->countries = Country::whereIn('id', $countryId)->get();
//        } elseif ($user->hasRole('Compliance Finance Manager')) {
//            $countryId = $user->country_id;
//            $this->countries = Country::whereIn('id', $countryId)->get();
//        } elseif ($user->hasRole('Compliance Principle Manager')) {
//            $countryId = $user->country_id;
//            $this->countries = Country::whereIn('id', $countryId)->get();
//        } elseif ($user->hasRole('Compliance Finance Officer')) {
//            $countryId = $user->country_id;
//            $this->countries = Country::whereIn('id', $countryId)->get();
//        } elseif ($user->hasRole('Compliance Principle Officer')) {
//            $countryId = $user->country_id;
//            $this->countries = Country::whereIn('id', $countryId)->get();
//        } else {
//
//            $this->countries = $this->country;
//        }
//        $this->country = Country::get();
        $this->events_regulars_operations = [];
//            CompliancePrimarySubMenu::where('event_type', 'Regular')->whereRelation('complianceSubMenu', 'sub_menu_name', 'Operations')->where('calendar_year_id', $this->calendar_year_id)->get();
        $this->events_regulars_finances = [];
//            CompliancePrimarySubMenu::where('event_type', 'Regular')->whereRelation('complianceSubMenu', 'sub_menu_name', 'Finance')->get();
        $this->events_regulars_hrs = [];
//            CompliancePrimarySubMenu::where('event_type', 'Regular')->whereRelation('complianceSubMenu', 'sub_menu_name', 'HR')->get();
        $this->events_addhocs_operations = [];
//            CompliancePrimarySubMenu::where('event_type', 'Add-Hoc')->whereRelation('complianceSubMenu', 'sub_menu_name', 'Operations')->get();
        $this->events_addhocs_finances = [];
//            CompliancePrimarySubMenu::where('event_type', 'Add-Hoc')->whereRelation('complianceSubMenu', 'sub_menu_name', 'Finance')->get();
        $this->events_addhocs_hrs = [];
//            CompliancePrimarySubMenu::where('event_type', 'Add-Hoc')->whereRelation('complianceSubMenu', 'sub_menu_name', 'HR')->get();
        $this->entities = Entity::get();

        $this->operations = ComplianceSubMenu::where('sub_menu_name', 'Operations')->groupBy('country_id')->pluck('country_id');
        $this->finances = ComplianceSubMenu::where('sub_menu_name', 'Finance')->groupBy('country_id')->pluck('country_id');
//        dd($this->operations);
//        $operations = ComplianceSubMenu::where('sub_menu_name', 'Operations')->get();
//
//        $this->operations = $operations->groupBy('country_id');
//        $this->operations = Document::get();

        $user = Auth::user();

//        if ($user->hasAnyRole(['Country Head', 'Cluster Head', 'Compliance Finance Manager', 'Compliance Principle Manager', 'Compliance Finance Officer', 'Compliance Principle Officer'])) {
//            $countryId = $user->country_id;
////            dd($countryId);
//            if ($countryId !== null) {
//                $this->countries = Country::whereIn('id', $countryId)->get();
//            } else {
//                // Set countries to an empty array if country_id is null
//                $this->countries = [];
//            }
//        } else {
//            $this->countries = $this->country;
//        }
//
////        $this->events = ComplianceEvent::get();
//        $user = Auth::user();
//        if ($user->hasAnyRole(['Country Head', 'Cluster Head', 'Compliance Finance Manager', 'Compliance Principle Manager', 'Compliance Finance Officer', 'Compliance Principle Officer'])) {
//            $countryId = $user->country_id;
////            dd($countryId);
//            if ($countryId !== null) {
//                $this->compliance_events = ComplianceEvent::whereIn('country_id', $countryId)->get();
//            } else {
//                $this->compliance_events = [];
//            }
//        } else {
//            $this->compliance_events = $this->events;
//        }
//        $user = auth()->user();

    }


    protected function getFormSchema(): array
    {
        return [
            Card::make()->schema([
                Select::make('calendar_year_id')->label('Financial Year')->columnSpan(1)->searchable()->reactive()
                    ->options(CalendarYear::pluck('name', 'id')->toArray())->columnSpan(1),

                Select::make('country_id')->columnSpan(1)
                    ->label('Country')->searchable()->preload()->reactive()
                    ->options(function (Get $get) {
                        if ($get('calendar_year_id')) {
                            $countryId = CalendarYear::where('id',$get('calendar_year_id'))->pluck('country_id')->flatten()->toArray();
                            return Country::whereIn('id',$countryId)->pluck('name', 'id');
                        } else {
                            return [];
                        }
                    }),
//                Select::make('country_id')->label('Country')->searchable()->reactive()
//                    ->options(Country::pluck('name', 'id'))->columnSpan(1),

                Select::make('entity_id')->label('Entity Name')->searchable()->reactive()
                    ->options(function (Get $get) {
                        if ($get('country_id')) {
                            return Entity::where('country_id', $get('country_id'))->pluck('entity_name', 'id');
                        } else {
                            return [];
                        }
                    })->columnSpan(1),


            ])->columns(5)
        ];
    }

    public function updated($value)
    {
//        dd($this->country_id);

        $events_regulars_operations = CompliancePrimarySubMenu::query()
            ->where('event_type', 'Regular')
            ->whereRelation('complianceSubMenu', 'sub_menu_name', 'Operations')
            ->where('calendar_year_id', $this->calendar_year_id);

        if ($this->country_id != null) {
            $events_regulars_operations = $events_regulars_operations->where('country_id', $this->country_id);
        }
        if ($this->entity_id != null) {
            $events_regulars_operations = $events_regulars_operations->where('entity_id', $this->entity_id);
        }

        $this->events_regulars_operations = $events_regulars_operations->get();




        $events_regulars_finances = CompliancePrimarySubMenu::query()
            ->where('event_type', 'Regular')
            ->whereRelation('complianceSubMenu', 'sub_menu_name', 'Finance')
            ->where('calendar_year_id', $this->calendar_year_id);

        if ($this->country_id != null) {
            $events_regulars_finances = $events_regulars_finances->where('country_id', $this->country_id);
        }
        if ($this->entity_id != null) {
            $events_regulars_finances = $events_regulars_finances->where('entity_id', $this->entity_id);
        }
        $this->events_regulars_finances = $events_regulars_finances->get();
//        dd($this->events_regulars_finances);


        $events_regulars_hrs = CompliancePrimarySubMenu::query()
            ->where('event_type', 'Regular')
            ->whereRelation('complianceSubMenu', 'sub_menu_name', 'HR')
            ->where('calendar_year_id', $this->calendar_year_id);

        if ($this->country_id != null) {
            $events_regulars_hrs = $events_regulars_hrs->where('country_id', $this->country_id);
        }
        if ($this->entity_id != null) {
            $events_regulars_hrs = $events_regulars_hrs->where('entity_id', $this->entity_id);
        }
        $this->events_regulars_hrs = $events_regulars_hrs->get();


        $events_addhocs_operations = CompliancePrimarySubMenu::query()
            ->where('event_type', 'Add-Hoc')
            ->whereRelation('complianceSubMenu', 'sub_menu_name', 'Operations')
            ->where('calendar_year_id', $this->calendar_year_id);

        if ($this->country_id != null) {
            $events_addhocs_operations = $events_addhocs_operations->where('country_id', $this->country_id);
        }
        if ($this->entity_id != null) {
            $events_addhocs_operations = $events_addhocs_operations->where('entity_id', $this->entity_id);
        }

        $this->events_addhocs_operations = $events_addhocs_operations->get();

        $events_addhocs_finances = CompliancePrimarySubMenu::query()
            ->where('event_type', 'Add-Hoc')
            ->whereRelation('complianceSubMenu', 'sub_menu_name', 'Finance')
            ->where('calendar_year_id', $this->calendar_year_id);

        if ($this->country_id != null) {
            $events_addhocs_finances = $events_addhocs_finances->where('country_id', $this->country_id);
        }
        if ($this->entity_id != null) {
            $events_addhocs_finances = $events_addhocs_finances->where('entity_id', $this->entity_id);
        }
        $this->events_addhocs_finances = $events_addhocs_finances->get();

        $events_addhocs_hrs = CompliancePrimarySubMenu::query()
            ->where('event_type', 'Add-Hoc')
            ->whereRelation('complianceSubMenu', 'sub_menu_name', 'HR')
            ->where('calendar_year_id', $this->calendar_year_id);
        if ($this->country_id != null) {
            $events_addhocs_hrs = $events_addhocs_hrs->where('country_id', $this->country_id);
        }
        if ($this->entity_id != null) {
            $events_addhocs_hrs = $events_addhocs_hrs->where('entity_id', $this->entity_id);
        }

        $this->events_addhocs_hrs = $events_addhocs_hrs->get();

    }


}
