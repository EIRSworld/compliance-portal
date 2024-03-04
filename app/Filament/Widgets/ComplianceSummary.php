<?php

namespace App\Filament\Widgets;

use App\Exports\DashboardSummaryExport;
use App\Models\CalendarYear;
use App\Models\Country;
use App\Models\User;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
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

    public $countries, $calendar_year_id, $country;

    public function mount()
    {

        $currentYear = Carbon::now()->year;
        $this->calendar_year_id = CalendarYear::where('name', $currentYear)->value('id');

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
        $this->country = Country::get();
        $user = Auth::user();

        if ($user->hasAnyRole(['Country Head', 'Cluster Head', 'Compliance Finance Manager', 'Compliance Principle Manager', 'Compliance Finance Officer', 'Compliance Principle Officer'])) {
            $countryId = $user->country_id;
            if ($countryId !== null) {
                $this->countries = Country::whereIn('id', [$countryId])->get();
            } else {
                // Set countries to an empty array if country_id is null
                $this->countries = [];
            }
        } else {
            $this->countries = $this->country;
        }
//        $user = auth()->user();

    }

    protected function getActions(): array
    {
        return [
            Action::make('export')->label('Export')->button()->icon('heroicon-m-arrow-down-tray')
                ->action(function () {
                    $file_name = 'Dashboard Summary Report.xlsx';
                    return Excel::download(new DashboardSummaryExport($this->calendar_year_id), $file_name);
                })
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            Section::make()->schema([
                Select::make('calendar_year_id')->label('Calendar Year')->columnSpan(1)->searchable()->reactive()
                    ->options(CalendarYear::pluck('name', 'id')->toArray())->columnSpan(1)

            ])->columns(6)
        ];
    }

//    public function updated($value)
//    {
//        $this->calendar_year_id = $value;
////        dd($value);
////        $calendarYear = CalendarYear::findOrFail($value);
//    }


}
