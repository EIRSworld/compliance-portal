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

    public $compliance_events, $calendar_year_id, $country,$user;
    public function mount()
    {

        $currentYear = Carbon::now()->year;
        $this->calendar_year_id = CalendarYear::where('name', $currentYear)->value('id');

        $this->compliance_events = ComplianceEvent::get();
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
        $this->user = Auth::user();

//        if ($user->hasAnyRole(['Country Head', 'Cluster Head', 'Compliance Finance Manager', 'Compliance Principle Manager', 'Compliance Finance Officer', 'Compliance Principle Officer']) && $user->country_id !== null) {
//            $this->compliance_events = ComplianceEvent::whereIn('country_id', [$user->country_id])->get();
//        }
//        else {
//            $this->compliance_events = ComplianceEvent::get();
//        }
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

//    public function table(Table $table): Table
//    {
//        return $table
//            ->query(function () {
//
////                $user = auth()->user();
////                if ($user->hasRole('Country Head')) {
////                    $userId = auth()->id();
////                    $user = User::find($userId);
////                    return \App\Models\ComplianceEvent::whereIn('country_id',$user->country_id);
////                }  elseif ($user->hasRole('Cluster Head')) {
////                    $userId = auth()->id();
////                    $user = User::find($userId);
////                    return \App\Models\ComplianceEvent::whereIn('country_id',$user->country_id);
////                } elseif ($user->hasRole('Compliance Finance Manager')) {
////                    $userId = auth()->id();
////                    $user = User::find($userId);
////                    return \App\Models\ComplianceEvent::whereIn('country_id',$user->country_id);
////                } elseif ($user->hasRole('Compliance Principle Manager')) {
////                    $userId = auth()->id();
////                    $user = User::find($userId);
////                    return \App\Models\ComplianceEvent::whereIn('country_id',$user->country_id);
////                } elseif ($user->hasRole('Compliance Finance Officer')) {
////                    $userId = auth()->id();
////                    $user = User::find($userId);
////                    return \App\Models\ComplianceEvent::whereIn('country_id',$user->country_id);
////                } elseif ($user->hasRole('Compliance Principle Officer')) {
////                    $userId = auth()->id();
////                    $user = User::find($userId);
////                    return \App\Models\ComplianceEvent::whereIn('country_id',$user->country_id);
////                }
//                $user = auth()->user();
//
//// Check if the user has a role that requires filtering by country_id and if country_id is available
//                if ($user->hasAnyRole(['Country Head', 'Cluster Head', 'Compliance Finance Manager', 'Compliance Principle Manager', 'Compliance Finance Officer', 'Compliance Principle Officer']) && $user->country_id !== null) {
//                    // Fetch compliance events based on the user's country_id
//                    return \App\Models\ComplianceEvent::whereIn('country_id', [$user->country_id])->get();
//                } else {
//                    return \App\Models\ComplianceEvent::query();
//                }
//            })
////            ->query(\App\Models\ComplianceEvent::query())
//            ->columns([
//                TextColumn::make('country.name')->label('Country Name')
//                    ->extraAttributes(function (ComplianceEvent $record) {
//                        if ($record->status == 'Red') {
//                            return [
//                                'class' => 'custom-bg-red-event',
//                            ];
//                        } elseif ($record->status == 'Amber') {
//                            return [
//                                'class' => 'custom-bg-yellow',
//                            ];
//                        } elseif ($record->status == 'Green') {
//                            return [
//                                'class' => 'custom-bg-green',
//                            ];
//                        } elseif ($record->status == 'Blue') {
//                            return [
//                                'class' => 'custom-bg-blue',
//                            ];
//                        }
//                        return [];
//                    }),
////                TextColumn::make('calendarYear.name')->label('Calendar Year'),
////                TextColumn::make('name')->label('Name'),
//                TextColumn::make('description')->label('Description')
//                    ->wrap()->getStateUsing(function (ComplianceEvent $record) {
//                        $com = $record->description;
//                        $comment = wordwrap($com, 35, "<br>\n");
//                        return '<div class="tooltip">
//        <p class="text-ellipsis" style="margin: 0">' . $comment . '</p>
//<span class="tooltip-text tooltip-text-left">' . $comment . '</span>
//</div>';
//                    })->html(),
////                    ->getStateUsing(function (ComplianceEvent $record) {
////                        return Str::limit($record->description, 20);
////                    })
////                    ->tooltip(fn(ComplianceEvent $record): string|null => $record->description),
//                TextColumn::make('status')->label('Status')
//                    ->getStateUsing(function (ComplianceEvent $record) {
//
//                        $data = $record->status ?? '-';
//
//                        if ($record->status == 'Red') {
//                            $data .= '<div class="filament-tables-badge-column flex ">
//                    <div class="inline-flex items-center text-left	 space-x-1 rtl:space-x-reverse min-h-6 px-2 py-0.5 text-sm font-medium tracking-tight rounded-xl whitespace-nowrap  bg-primary-500/10">
//
//                    <span>(Very Critical)';
//                            $data .= '</span></div></div>';
//                        } elseif ($record->status == 'Amber') {
//                            $data .= '<div class="filament-tables-badge-column flex ">
//                    <div class="inline-flex items-center text-left	 space-x-1 rtl:space-x-reverse min-h-6 px-2 py-0.5 text-sm font-medium tracking-tight rounded-xl whitespace-nowrap  bg-primary-500/10">
//
//                    <span>(Event needs attention)';
//                            $data .= '</span></div></div>';
//                        } elseif ($record->status == 'Green') {
//                            $data .= '<div class="filament-tables-badge-column flex ">
//                    <div class="inline-flex items-center text-left	 space-x-1 rtl:space-x-reverse min-h-6 px-2 py-0.5 text-sm font-medium tracking-tight rounded-xl whitespace-nowrap  bg-primary-500/10">
//
//                    <span>(Solved - It is a risk, but it keeps happening)';
//                            $data .= '</span></div></div>';
//                        } elseif ($record->status == 'Blue') {
//                            $data .= '<div class="filament-tables-badge-column flex ">
//                    <div class="inline-flex items-center text-left	 space-x-1 rtl:space-x-reverse min-h-6 px-2 py-0.5 text-sm font-medium tracking-tight rounded-xl whitespace-nowrap  bg-primary-500/10">
//
//                    <span>(Event happened but its not a risk anymore)';
//                            $data .= '</span></div></div>';
//                        } else {
//                            $data .= '';
//                        }
//
//                        return $data;
//                    })->html(),
//            ])
//            ->filters([
//                SelectFilter::make('calendar_year_id')->searchable()
//                    ->options(function () {
//                        return CalendarYear::pluck('name', 'id')->toArray();
//                    })
//                    ->default(function () {
//                        $currentYear = Carbon::now()->year;
//                        return CalendarYear::where('name', $currentYear)->value('id');
//                    })
//                    ->placeholder('Select the Year')
//                    ->label('Year'),
//            ], FiltersLayout::AboveContent);
//    }
}
