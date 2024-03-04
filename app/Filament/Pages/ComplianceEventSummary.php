<?php

namespace App\Filament\Pages;

use App\Enums\EventStatus;
use App\Exports\EventExport;
use App\Models\CalendarYear;
use App\Models\ComplianceEvent;
use App\Models\ComplianceMenu;
use App\Models\Country;
use App\Models\User;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ComplianceEventSummary extends Page implements HasTable
{
    use InteractsWithTable;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.compliance-event-summary';

    protected static ?string $navigationLabel = 'Compliance Event Summary';

    protected static ?string $title = 'Compliance Event Summary';

    protected static ?int $navigationSort = 2;


    protected function getHeaderActions(): array
    {
        return [
            Action::make('export')
                ->label('Download All Events')->color('success')
                ->icon('heroicon-o-arrow-down-circle')
                ->action(function () {
                    return Excel::download(new EventExport($this->getFilteredTableQuery()->get()), 'Event.xlsx',);
                }),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\ComplianceEvent::query())
            ->columns([
                TextColumn::make('country.name')->label('Country')
                    ->extraAttributes(function (ComplianceEvent $record) {
                        if ($record->status == 'Red') {
                            return [
                                'class' => 'custom-bg-red-event',
                            ];
                        } elseif ($record->status == 'Amber') {
                            return [
                                'class' => 'custom-bg-yellow',
                            ];
                        } elseif ($record->status == 'Green') {
                            return [
                                'class' => 'custom-bg-green',
                            ];
                        }elseif ($record->status == 'Blue') {
                            return [
                                'class' => 'custom-bg-blue',
                            ];
                        }
                        return [];
                    }),
                TextColumn::make('calendarYear.name')->label('Calendar Year'),
                TextColumn::make('name')->label('Event Name'),
                TextColumn::make('description')->label('Description')
                    ->wrap()
                    ->getStateUsing(function (ComplianceEvent $record) {
                        return Str::limit($record->description, 20);
                    })
                    ->tooltip(fn(ComplianceEvent $record): string|null => $record->description),
//                    ->getStateUsing(function (ComplianceEvent $record) {
//                        $com = $record->description;
//                        $comment = wordwrap($com, 35, "<br>\n");
//                        return '<div class="tooltip">
//        <p class="text-ellipsis" style="margin: 0">' . $comment . '</p>
//<span class="tooltip-text tooltip-text-left">' . $comment . '</span>
//</div>';
//                    })->html(),
//                    ->getStateUsing(function (ComplianceEvent $record) {
//                        return Str::limit($record->description, 20);
//                    })
//                    ->tooltip(fn(ComplianceEvent $record): string|null => $record->description),
                TextColumn::make('status')->label('Status')
                    ->getStateUsing(function (ComplianceEvent $record) {

                    $data = $record->status ?? '-';

                    if($record->status == 'Red') {
                        $data .= '<div class="filament-tables-badge-column flex ">
                    <div class="inline-flex items-center text-left	 space-x-1 rtl:space-x-reverse min-h-6 px-2 py-0.5 text-sm font-medium tracking-tight rounded-xl whitespace-nowrap  bg-primary-500/10">

                    <span>(Very Critical)';
                        $data .= '</span></div></div>';
                    }
                    elseif($record->status == 'Amber') {
                        $data .= '<div class="filament-tables-badge-column flex ">
                    <div class="inline-flex items-center text-left	 space-x-1 rtl:space-x-reverse min-h-6 px-2 py-0.5 text-sm font-medium tracking-tight rounded-xl whitespace-nowrap  bg-primary-500/10">

                    <span>(Event needs attention)';
                        $data .= '</span></div></div>';
                    }
                    elseif($record->status == 'Green') {
                        $data .= '<div class="filament-tables-badge-column flex ">
                    <div class="inline-flex items-center text-left	 space-x-1 rtl:space-x-reverse min-h-6 px-2 py-0.5 text-sm font-medium tracking-tight rounded-xl whitespace-nowrap  bg-primary-500/10">

                    <span>(Solved - It is a risk, but it keeps happening)';
                        $data .= '</span></div></div>';
                    }
                    elseif($record->status == 'Blue') {
                        $data .= '<div class="filament-tables-badge-column flex ">
                    <div class="inline-flex items-center text-left	 space-x-1 rtl:space-x-reverse min-h-6 px-2 py-0.5 text-sm font-medium tracking-tight rounded-xl whitespace-nowrap  bg-primary-500/10">

                    <span>(Event happened but its not a risk anymore)';
                        $data .= '</span></div></div>';
                    }
                    else{
                        $data .= '';
                    }

                    return $data;
                })->html(),
            ])
            ->filters([
                SelectFilter::make('calendar_year_id')->searchable()
                    ->options(function () {
                        return CalendarYear::pluck('name', 'id')->toArray();
                    })

                    ->default(function(){
                        $currentYear = Carbon::now()->year;
                        return CalendarYear::where('name', $currentYear)->value('id');
                    })
                    ->placeholder('Select the Year')
                    ->label('Year'),
                SelectFilter::make('country_id')->searchable()
                    ->options(function () {
                        return Country::pluck('name', 'id')->toArray();
                    })
                    ->placeholder('Select the Country')
                    ->label('Country'),
                SelectFilter::make('status')->searchable()
                    ->options(collect(EventStatus::asSelectArray()))
                    ->placeholder('Select the Status')
                    ->label('Status'),
            ], FiltersLayout::AboveContent)

            ->actions([
                \Filament\Tables\Actions\Action::make('edit')->color('warning')->button()
                    ->icon('heroicon-o-pencil')->modalWidth('lg')
                    ->label('Edit')
                    ->mountUsing(function (ComponentContainer $form,$record) {
                        $form->fill([
                            'country_id' => $record->country_id,
                            'calendar_year_id' => $record->calendar_year_id,
                            'description' => $record->description,
                            'name' => $record->name,
                            'status' => $record->status,
                        ]);
                    })
                    ->form([
                        Card::make()
                            ->schema([
                                Select::make('calendar_year_id')
                                    ->label('Calendar Year')->searchable()->preload()
                                    ->options(CalendarYear::pluck('name', 'id')),
                                Select::make('country_id')
                                    ->label('Country')->searchable()->preload()
                                    ->options(Country::pluck('name', 'id')),
                                TextInput::make('name')
                                    ->columnSpan(1)
                                    ->label('Name')
                                    ->required(),
                                Textarea::make('description')
                                    ->columnSpan(1)
                                    ->label('Description')
                                    ->required(),

                                Select::make('status')
                                    ->options(collect(EventStatus::asSelectArray()))
                                    ->searchable()->reactive(),


                                Placeholder::make('red')->label('')
                                    ->content(new HtmlString('<h1 style="color:#fa3e3e; font-size: 15px;text-align: Left;margin-top: 36px;"> (Very Critical) </h1>'))
                                    ->visible(function (callable $get) {
                                        if ($get('status') === EventStatus::Red) {
                                            return true;
                                        }
                                        return false;

                                    }),
                                Placeholder::make('amber')->label('')
                                    ->content(new HtmlString('<h1 style="color:#d5b94d; font-size: 15px;text-align: Left;margin-top: 36px;"> (Event needs attention) </h1>'))
                                    ->visible(function (callable $get) {
                                        if ($get('status') === EventStatus::Amber) {
                                            return true;
                                        }
                                        return false;

                                    }),
                                Placeholder::make('green')->label('')
                                    ->content(new HtmlString('<h1 style="color:#179a17; font-size: 15px;text-align: Left;margin-top: 36px;"> (Solved - It is a risk, but it keeps happening) </h1>'))
                                    ->visible(function (callable $get) {
                                        if ($get('status') === EventStatus::Green) {
                                            return true;
                                        }
                                        return false;

                                    }),
                                Placeholder::make('blue')->label('')
                                    ->content(new HtmlString('<h1 style="color:#73abec; font-size: 15px;text-align: Left;margin-top: 36px;"> (Event happened but its not a risk anymore.) </h1>'))
                                    ->visible(function (callable $get) {
                                        if ($get('status') === EventStatus::Blue) {
                                            return true;
                                        }
                                        return false;

                                    }),
                            ])
                    ])

                    ->action(function (array $data, $record, $form): void {
                        $complianceEvent = ComplianceEvent::find($record->id);
                        $complianceEvent->calendar_year_id = $data['calendar_year_id'];
                        $complianceEvent->country_id = $data['country_id'];
                        $complianceEvent->name = $data['name'];
                        $complianceEvent->description = $data['description'];
                        $complianceEvent->status = $data['status'];
                        $complianceEvent->save();

                        Notification::make()
                            ->title('Event Successfully Updated')
                            ->success()
                            ->send();

                        if ($data['status'] == 'Red') {

                            $user = User::role('Management')->first();
                            $data = [
                                'subject' => 'Reminder Email',
                                'name' => $user->name,
                                'email' => $user->email,
                                'complianceEvent' => $complianceEvent,
                            ];

                            Mail::send('mail.event-mail', $data, function ($message) use ($data,$user) {
                                $message->to($user->email, config('app.name'));
                            });
                            Notification::make()
                                ->title('Mail Sent Successfully')
                                ->success()
                                ->send();
                        }
                    }),

                \Filament\Tables\Actions\Action::make('delete')->color('danger')
                    ->icon('heroicon-o-trash')
                    ->label('Delete')
                    ->button()
                    ->requiresConfirmation()
                    ->action(function (array $data, $record, $form): void {
                        $complianceEvent = ComplianceEvent::find($record->id)->delete();
                        Notification::make()
                            ->title('Deleted Successfully')
                            ->success()
                            ->send();
                    }),
            ], position: ActionsPosition::AfterColumns);
    }
}
