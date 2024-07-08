<?php

namespace App\Filament\Widgets;

use App\Exports\DashboardSummaryExport;
use App\Models\CalendarYear;
use App\Models\CompliancePrimarySubMenu;
use App\Models\ComplianceSubMenu;
use App\Models\Country;
use App\Models\Entity;
use App\Models\User;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PHPUnit\Framework\Constraint\Count;

class NewDashboard extends Widget implements HasForms
{

    use InteractsWithForms;

    protected static string $view = 'filament.widgets.new-dashboard';
    protected static ?string $heading = 'Dashboard Summary Report';
    protected int|string|array $columnSpan = 2;
    public $calendar_year_id, $country_id, $entity_id, $compliance_sub_menu_id, $country, $entity;
    public $regular_yearly,$event_name_yearly,$calendarYear;

    public function mount()
    {

        $now = Carbon::now();
        $currentYear = $now->year;
        if ($now->format('Y-m-d') <= $currentYear . "-03-31") {
            $previousYear = $currentYear - 1;
            $financeYear = $previousYear . '-' . $currentYear;
        } else {
            $nextYear = $currentYear + 1;
            $financeYear = $currentYear . '-' . $nextYear;
        }

        $this->calendarYear = CalendarYear::whereName($financeYear)->first();
        $this->calendar_year_id =  $this->calendarYear->id;

        if(auth()->user()->hasRole('Compliance Officer') || auth()->user()->hasRole('Cluster Head') || auth()->user()->hasRole('Country Head')) {
            $country_id = User::whereId(auth()->user()->id)->value('country_id');
            $this->country = Country::whereId($country_id[0])->first();
        }else{
            $this->country = Country::whereId(1)->first();
        }
        $this->entity = Entity::whereCountryId($this->country->id)
//            ->where('entity_type', '=', 'Insurance')
            ->first();
        $this->country_id = $this->country->id;
        $this->entity_id = $this->entity->id;

        if(auth()->user()->hasRole('Compliance Manager')){
            $user = User::find(auth()->user()->id);

            $this->regular_yearly = ComplianceSubMenu::whereCalendarYearId($this->calendar_year_id)->whereCountryId($this->country_id)->whereEntityId($this->entity_id)->whereSubMenuName($user->compliance_type)->get();
        }
//        elseif(auth()->user()->hasRole('Compliance Officer')){
//            $country_id = User::whereId(auth()->user()->id)->value('country_id');
//
//        $this->regular_yearly = ComplianceSubMenu::whereCountryId($country_id[0])->whereEntityId($this->entity_id)->get();
//        }
        else{

        $this->regular_yearly = ComplianceSubMenu::whereCalendarYearId($this->calendar_year_id)->whereCountryId($this->country_id)->whereEntityId($this->entity_id)->get();
        }


    }

    public function updated($value)
    {
        if ($value === 'calendar_year_id') {
//            if (empty($this->calendar_year_id)) {
                $this->country_id = '';
                $this->entity_id = '';
                $this->compliance_sub_menu_id = '';
            if(auth()->user()->hasRole('Compliance Manager')){
                $user = User::find(auth()->user()->id);

                $this->regular_yearly = ComplianceSubMenu::whereCalendarYearId($this->calendar_year_id)->whereCountryId($this->country_id)->whereEntityId($this->entity_id)->whereSubMenuName($user->compliance_type)->get();
            }else{
                $this->regular_yearly = ComplianceSubMenu::whereCalendarYearId($this->calendar_year_id)->where('country_id', $this->country_id)
                    ->where('entity_id', $this->entity_id)
                    ->get();

            }

        }
        if ($value === 'country_id') {
            $this->country = Country::find($this->country_id);
                $this->entity_id = '';
                $this->compliance_sub_menu_id = '';
            if(auth()->user()->hasRole('Compliance Manager')){
                $user = User::find(auth()->user()->id);

                $this->regular_yearly = ComplianceSubMenu::whereCalendarYearId($this->calendar_year_id)->whereCountryId($this->country_id)->whereEntityId($this->entity_id)->whereSubMenuName($user->compliance_type)->get();
            }else{
                $this->regular_yearly = ComplianceSubMenu::whereCalendarYearId($this->calendar_year_id)->where('country_id', $this->country_id)
                    ->where('entity_id', $this->entity_id)
                    ->get();

            }
        }
        if ($value === 'entity_id') {
            $this->entity = Entity::find($this->entity_id);
//            if (empty($this->entity_id)) {
                $this->compliance_sub_menu_id = '';
            if(auth()->user()->hasRole('Compliance Manager')){
                $user = User::find(auth()->user()->id);

                $this->regular_yearly = ComplianceSubMenu::whereCalendarYearId($this->calendar_year_id)->whereCountryId($this->country_id)->whereEntityId($this->entity_id)->whereSubMenuName($user->compliance_type)->get();
            }else{
                $this->regular_yearly = ComplianceSubMenu::whereCalendarYearId($this->calendar_year_id)->where('country_id', $this->country_id)
                    ->where('entity_id', $this->entity_id)
                    ->get();
            }
        }
        if ($value === 'compliance_sub_menu_id') {
            if(auth()->user()->hasRole('Compliance Manager')){
                $user = User::find(auth()->user()->id);

                $this->regular_yearly = ComplianceSubMenu::whereCalendarYearId($this->calendar_year_id)->whereCountryId($this->country_id)->whereEntityId($this->entity_id)->whereSubMenuName($user->compliance_type)->get();
            }elseif(empty($this->compliance_sub_menu_id)){
                $this->regular_yearly = ComplianceSubMenu::whereCalendarYearId($this->calendar_year_id)->where('country_id', $this->country_id)
                    ->where('entity_id', $this->entity_id)
                    ->get();
            }
            else{
            $this->regular_yearly = ComplianceSubMenu::where('id', $this->compliance_sub_menu_id)->get();

            }
        }
    }


    protected function getFormSchema(): array
    {
        return [
            Card::make()->schema([
                Select::make('calendar_year_id')->label('Financial Year')->columnSpan(1)->searchable()->reactive()
                    ->options(CalendarYear::pluck('name', 'id')->toArray())->columnSpan(1),

                Select::make('country_id')->columnSpan(1)
                    ->label('Country')->searchable()->preload()->reactive()
                    ->options(function (Get $get, callable $set) {
                        if ($get('calendar_year_id')) {
                            $countryId = CalendarYear::where('id', $get('calendar_year_id'))->pluck('country_id')->flatten()->toArray();
                            return Country::whereIn('id', $countryId)->pluck('name', 'id');
                        } else {
                            return [];
                        }
                    }),

                Select::make('entity_id')->label('Entity Name')->searchable()->reactive()
                    ->options(function (Get $get) {
                        if ($get('country_id')) {
                            return Entity::where('country_id', $get('country_id'))->pluck('entity_name', 'id');
                        } else {
                            return [];
                        }
                    })->columnSpan(1),

                Select::make('compliance_sub_menu_id')->columnSpan(1)
                    ->label('Compliance Type')->searchable()->reactive()
                    ->options(function (callable $get) {
//                        dd($get('entity_id'));

                        if ($get('entity_id') && $get('country_id') && $get('calendar_year_id')) {
                            if (!auth()->user()->hasRole('Compliance Manager')) {

                                return ComplianceSubMenu::where('calendar_year_id', $get('calendar_year_id'))->where('country_id', $get('country_id'))->where('entity_id', $get('entity_id'))->pluck('sub_menu_name', 'id');
                            } elseif (auth()->user()->hasRole('Compliance Manager')) {
                                $user = User::whereId(auth()->user()->id)->first();
                                return ComplianceSubMenu::where('calendar_year_id', $get('calendar_year_id'))->where('country_id', $get('country_id'))->where('entity_id', $get('entity_id'))->where('sub_menu_name', '=', $user->compliance_type)->pluck('sub_menu_name', 'id');
                            }
                        } else {
                            return [];
                        }
                    })
                ->visible(function (){
                    if (!auth()->user()->hasRole('Compliance Manager'))
                    {
                        return true;
                    }
                    return false;
                }),


            ])->columns(5)
        ];
    }

}
