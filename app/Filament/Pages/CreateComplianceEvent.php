<?php

namespace App\Filament\Pages;

use App\Enums\EventStatus;
use App\Models\CalendarYear;
use App\Models\ComplianceEvent;
use App\Models\CompliancePrimarySubMenu;
use App\Models\ComplianceSubMenu;
use App\Models\Country;
use App\Models\Entity;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;

class CreateComplianceEvent extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.create-compliance-event';

    protected static ?string $navigationLabel = 'Create Event';

    protected static ?string $title = 'Create Event';

    public $event_details = [];

    protected static ?int $navigationSort = 1;

    public static function canAccess(): bool
    {
        return auth()->user()->can('create Compliance Event');
    }

//    public static function shouldRegisterNavigation(): bool
//    {
//        if (auth()->user()->can('Create Compliance Event')) {
//            return true;
//        }
//        return false;
//    }

    public $calendar_year_id, $country_id, $event_name, $status, $status_text, $due_date, $entity_id, $type, $occurrence, $assign_id, $compliance_sub_menu_id, $event_type, $statusColor;

    public function mount()
    {

        $currentYear = Carbon::now()->year;

        $calendarYear = CalendarYear::where('name', $currentYear)->value('id');
        $this->calendar_year_id = $calendarYear;


    }

    public function updated($value)
    {
        if ($value === 'occurrence') {
            if ($this->occurrence == 'Monthly') {
                $this->event_details = [];
                for ($i = 1; $i <= 12; $i++) {
                    $this->event_details[] =
                        [
                            'due_date' => '',
                            'assign_id' => null,
                            'status' => 'Amber',
                        ];
                }
            }
            if ($this->occurrence == 'Yearly') {
                $this->event_details = [];
                for ($i = 1; $i <= 1; $i++) {
                    $this->event_details[] =
                        [
                            'due_date' => '',
                            'assign_id' => null,
                            'status' => 'Amber',
                        ];
                }
            }
            if ($this->occurrence == 'Qtr') {
                $this->event_details = [];
                for ($i = 1; $i <= 4; $i++) {
                    $this->event_details[] =
                        [
                            'due_date' => '',
                            'assign_id' => null,
                            'status' => 'Amber',
                        ];
                }
            }
        }
    }

    protected function getFormModel(): Model|string|null
    {
        return CompliancePrimarySubMenu::class;
    }

    protected function getFormSchema(): array
    {
        return [
//            Card::make([

            Card::make([
                Select::make('calendar_year_id')->reactive()
                    ->label('Financial Year')->searchable()->preload()
                    ->options(CalendarYear::pluck('name', 'id'))->columnSpan(3),
                Select::make('country_id')->columnSpan(3)
                    ->label('Country')->searchable()->preload()->reactive()
                    ->options(function (Get $get) {

//                        $user = auth()->user();
//
//                        if (auth()->user()->hasRole('Compliance Manager')) {
////                            $userCountry = User::whereId(auth()->user()->id)->first();
//                            $userCountryIds = $user->countries->pluck('id')->toArray();
////                            $userCountryIds = $userCountry->pluck('country_id')->toArray(); // Assuming a relation `countries` in User model
//                            if ($get('calendar_year_id')) {
//                                $countryId = CalendarYear::where('id', $get('calendar_year_id'))->pluck('country_id')->flatten()->toArray();
//                                return Country::whereIn('id', $countryId)
//                                    ->whereIn('id', $userCountryIds) // Filter by user countries
//                                    ->pluck('name', 'id');
//                            } else {
//                                return Country::whereIn('id', $userCountryIds)->pluck('name', 'id'); // Only show user countries
//                            }
//                        } else {
//                            if ($get('calendar_year_id')) {
//                                $countryId = CalendarYear::where('id', $get('calendar_year_id'))->pluck('country_id')->flatten()->toArray();
//                                return Country::whereIn('id', $countryId)->pluck('name', 'id');
//                            } else {
//                                return [];
//                            }
//                        }
                        if ($get('calendar_year_id')) {
                            $countryId = CalendarYear::where('id', $get('calendar_year_id'))->pluck('country_id')->flatten()->toArray();
                            return Country::whereIn('id', $countryId)->pluck('name', 'id');
                        } else {
                            return [];
                        }
                    }),
                Select::make('entity_id')->columnSpan(3)
                    ->label('Entity Name')->searchable()->preload()->reactive()
                    ->options(function (Get $get) {
                        if ($get('country_id')) {
                            return Entity::where('country_id', $get('country_id'))->pluck('entity_name', 'id');
                        } else {
                            return [];
                        }
                    }),
                Select::make('compliance_sub_menu_id')->columnSpan(3)
                    ->label('Compliance Type')->searchable()->reactive()
                    ->options(function (callable $get) {
//                        dd($get('entity_id'));

                        if ($get('entity_id') && $get('country_id')) {
                            if (auth()->user()->hasRole('Super Admin')) {

                                return ComplianceSubMenu::where('calendar_year_id', $this->calendar_year_id)->where('country_id', $get('country_id'))->where('entity_id', $get('entity_id'))->pluck('sub_menu_name', 'id');
                            } elseif (auth()->user()->hasRole('Compliance Manager')) {
                                $user = User::whereId(auth()->user()->id)->first();
                                return ComplianceSubMenu::where('calendar_year_id', $this->calendar_year_id)->where('country_id', $get('country_id'))->where('entity_id', $get('entity_id'))->where('sub_menu_name','=',$user->compliance_type )->pluck('sub_menu_name', 'id');
                            }
                        } else {
                            return [];
                        }
                    }),
                TextInput::make('event_name')->columnSpan(3)
                    ->label('Event Name')->maxLength(30)
                    ->required(),
                Radio::make('event_type')->inline()->reactive()
                    ->options([
                        'Regular' => 'Regular',
                        'Add-Hoc' => 'Add-Hoc',
                    ])->columnSpan(3),
//                    Textarea::make('description')
//                        ->columnSpan(3)
//                        ->rows(2)
//                        ->label('Description')
//                        ->required(),

                Radio::make('occurrence')->inline()->reactive()
                    ->options([
                        'Monthly' => 'Monthly',
                        'Yearly' => 'Yearly',
                        'Qtr' => 'Qtr'
                    ])->columnSpan(3),

            ])->columns(12)->columnSpan(12),

            Card::make([
                TableRepeater::make('event_details')->schema([
                    DatePicker::make('due_date')->reactive()
                        ->label('Due Date')->displayFormat('d-m-Y')
                        ->suffixIcon('heroicon-o-calendar')
                        ->closeOnDateSelection()->columnSpan(2)
                        ->afterStateUpdated(function (Get $get, Set $set){
                            if($get('due_date') <= Carbon::now()){
                                $set('status','Red');
                            }
                            else{
                                $set('status','Amber');
                            }
                        })
                        ->native(false)
                        ->extraAttributes(function (Get $get) {
                            if ($get('status') == EventStatus::Red) {
                                return ['style' => 'background:#fcc4c4;width:500px'];
                            } elseif ($get('status') == EventStatus::Amber) {
                                return ['style' => 'background:#ffeec7;width:500px'];
                            }
                            return [];
                        }),

                    Select::make('assign_id')
                        ->label('Assign to')->searchable()->preload()->reactive()
                        ->options(function (Get $get) {
                            if ($get('../../country_id')) {
                                return User::whereJsonContains('country_id', $get('../../country_id'))->role(['Compliance Officer', 'Cluster Head', 'Country Head'])->pluck('name', 'id');
                            } else {
                                return [];
                            }
                        })->placeholder('Select')->columnSpan(2)
                        ->extraAttributes(function (Get $get) {
                            if ($get('status') == EventStatus::Red) {
                                return ['style' => 'background:#fcc4c4;width:500px'];
                            } elseif ($get('status') == EventStatus::Amber) {
                                return ['style' => 'background:#ffeec7;width:500px'];
                            }
                            return [];
                        }),

                    Select::make('status')
                        ->columnSpan(2)
                        ->options([
                            'Red' => 'Red (Very Critical)',
                            'Amber' => 'Amber (Event needs attention)',
                        ])
                        ->default('Amber')
                        ->reactive()
                        ->searchable()
                        ->placeholder('Select Status')
                        ->extraAttributes(function (Get $get) {
                            if ($get('status') == EventStatus::Red) {
                                return ['style' => 'background:#fcc4c4;width:500px'];
                            } elseif ($get('status') == EventStatus::Amber) {
                                return ['style' => 'background:#ffeec7;width:500px'];
                            }
                            return [];
                        }),

//                        Select::make('status')->columnSpan(2)
//                            ->options([
//
//                                'Red' => 'Red (Very Critical)',
//                                'Amber' => 'Amber (Event needs attention)',
//                            ])
//                            ->default('Amber')
//                            ->afterStateUpdated(function (callable $set, $state) {
//                                $set('statusColor', match($state) {
//                                    'Red' => 'class' => 'custom-bg-yellow',
//                                    'Amber' => 'class' => 'custom-bg-red-event',
//                                });
//                            })
//                            ->searchable()->reactive()->placeholder('Select'),
//                        TextInput::make('statusColor')->disabled(),
//                    TextInput::make('red')->disabled()
//                        ->label('(Very Critical)')
////                        ->content(new HtmlString('<h1 style="color:#fa3e3e; font-size: 15px;text-align: Left;margin-top: 36px;"> (Very Critical) </h1>'))
//                        ->visible(function (callable $get) {
//                            if ($get('status') == EventStatus::Red) {
//                                return true;
//                            }
//                            return false;
//                        }),
//
//                    TextInput::make('amber')->disabled()
//                        ->label('(Event needs attention)')
////                        ->content(new HtmlString('<h1 style="color:#d5b94d; font-size: 15px;text-align: Left;margin-top: 36px;"> (Event needs attention) </h1>'))
//                        ->visible(function (callable $get) {
//                            if ($get('status') == EventStatus::Amber) {
//                                return true;
//                            }
//                            return false;
//                        }),
                ]),

            ])->columns(12)->columnSpan(12)
//            ])->columns(12)->columnSpan(12)


//                Placeholder::make('green')
//                    ->label('')
//                    ->content(new HtmlString('<h1 style="color:#179a17; font-size: 15px;text-align: Left;margin-top: 36px;"> (Solved - It is a risk, but it keeps happening) </h1>'))
//                    ->visible(fn(callable $get) => $get('status') === EventStatus::Green),
//
//                Placeholder::make('blue')
//                    ->label('')
//                    ->content(new HtmlString('<h1 style="color:#73abec; font-size: 15px;text-align: Left;margin-top: 36px;"> (Event happened but its not a risk anymore) </h1>'))
//                    ->visible(fn(callable $get) => $get('status') === EventStatus::Blue),


        ];
    }

    public function submit()
    {
        try {
            DB::beginTransaction();

//dd($this->event_details,$this->occurrence);

            $complianceSubMenu = ComplianceSubMenu::find($this->compliance_sub_menu_id);
            $calendarYear = CalendarYear::find($this->calendar_year_id);

            foreach ($this->event_details as $event_detail) {

                $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                $compliancePrimarySubMenu->calendar_year_id = $this->calendar_year_id;
                $compliancePrimarySubMenu->year = $calendarYear->name;
                $compliancePrimarySubMenu->country_id = $this->country_id;
                $compliancePrimarySubMenu->entity_id = $this->entity_id;
                $compliancePrimarySubMenu->document_id = $complianceSubMenu->document_id;
                $compliancePrimarySubMenu->compliance_menu_id = $complianceSubMenu->compliance_menu_id;
                $compliancePrimarySubMenu->compliance_sub_menu_id = $this->compliance_sub_menu_id;
                $compliancePrimarySubMenu->occurrence = $this->occurrence;
                $compliancePrimarySubMenu->event_name = $this->event_name;
//                $compliancePrimarySubMenu->description = $this->description;
                $compliancePrimarySubMenu->event_type = $this->event_type;
                $compliancePrimarySubMenu->due_date = $event_detail['due_date'];
                $compliancePrimarySubMenu->assign_id = $event_detail['assign_id'];
                $compliancePrimarySubMenu->status = $event_detail['status'];
                $compliancePrimarySubMenu->save();
            }

            DB::commit();

            Notification::make()
                ->title('Event Successfully Created')
                ->success()
                ->send();

//            if ($this->status == 'Red') {
//
//                $user = User::role('Management')->first();
//                $data = [
//                    'subject' => 'Reminder Email',
//                    'name' => $user->name,
//                    'email' => $user->email,
//                    'complianceEvent' => $complianceEvent,
//                ];
//
//                Mail::send('mail.event-mail', $data, function ($message) use ($data, $user) {
//                    $message->to($user->email, config('app.name'));
//                });
//                Notification::make()
//                    ->title('Mail Sent Successfully')
//                    ->success()
//                    ->send();
//            }

//            return back();
            return redirect(ComplianceManagement::getUrl());
        } catch (\Exception $exception) {
            Log::emergency("Exception while adding Folder " . $exception->getMessage() . " on line " . $exception->getLine());

            Notification::make()
                ->title($exception->getMessage())
                ->danger()
                ->send();
        }
    }
}
