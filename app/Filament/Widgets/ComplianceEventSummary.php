<?php

namespace App\Filament\Widgets;

use App\Enums\EventStatus;
use App\Exports\EventExport;
use App\Models\CalendarYear;
use App\Models\ComplianceEvent;
use App\Models\Country;
use App\Models\User;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Widgets\Widget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;
use Maatwebsite\Excel\Facades\Excel;

class ComplianceEventSummary extends Widget implements HasForms
{

    use InteractsWithForms;

    protected static string $view = 'filament.widgets.compliance-event-summary';

    protected int|string|array $columnSpan = 2;
    protected static ?string $heading = 'Compliance Event';

    public $compliance_events, $calendar_year_id, $country,$events;
    public function mount()
    {

        $currentYear = Carbon::now()->year;
        $this->calendar_year_id = CalendarYear::where('name', $currentYear)->value('id');
//        dd($this->calendar_year_id);


        $this->events = ComplianceEvent::get();
        $user = Auth::user();
        if ($user->hasAnyRole(['Country Head', 'Cluster Head', 'Compliance Finance Manager', 'Compliance Principle Manager', 'Compliance Finance Officer', 'Compliance Principle Officer'])) {
            $countryId = $user->country_id;
//            dd($countryId);
            if ($countryId !== null) {
                $this->compliance_events = ComplianceEvent::whereIn('country_id', $countryId)->get();
            } else {
                $this->compliance_events = [];
            }
        } else {
            $this->compliance_events = $this->events;
        }
    }
    protected function getFormSchema(): array
    {
        return [
            Card::make()->schema([
                Select::make('calendar_year_id')->label('Calendar Year')->columnSpan(1)->searchable()->reactive()
                    ->options(CalendarYear::pluck('name', 'id')->toArray())->columnSpan(1)

            ])->columns(6)
        ];
    }

}
