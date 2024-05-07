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
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;

class CreateComplianceEvent extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.create-compliance-event';

    protected static ?string $navigationLabel = 'Create Compliance Event';

    protected static ?string $title = 'Create Compliance Event';

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

    public $calendar_year_id, $country_id, $event_name, $description, $status, $status_text, $due_date, $entity_id, $type, $occurrence, $assign_id, $compliance_sub_menu_id;

    public function mount()
    {

        $currentYear = Carbon::now()->year;

        $calendarYear = CalendarYear::where('name', $currentYear)->value('id');
        $this->calendar_year_id = $calendarYear;


    }

    public function updated($value)
    {
        if ($value === 'occurrence') {
            if ($this->occurrence == 'Monthly'){
                $this->event_details = [];
                for ($i = 1; $i <= 12; $i++) {
                    $this->event_details[] =
                        [
                            'due_date' => '',
                            'event_name' => '',
                            'description' => '',
                            'assign_id' => null,
                            'status' => '',
                        ];
                }
            }
            if ($this->occurrence == 'Yearly'){
                $this->event_details = [];
                for ($i = 1; $i <= 1; $i++) {
                    $this->event_details[] =
                        [
                            'due_date' => '',
                            'event_name' => '',
                            'description' => '',
                            'assign_id' => null,
                            'status' => '',
                        ];
                }
            }
            if ($this->occurrence == 'Qtr'){
                $this->event_details = [];
                for ($i = 1; $i <= 4; $i++) {
                    $this->event_details[] =
                        [
                            'due_date' => '',
                            'event_name' => '',
                            'description' => '',
                            'assign_id' => null,
                            'status' => '',
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

            Card::make([
                Select::make('calendar_year_id')
                    ->label('Calendar Year')->searchable()->preload()
                    ->options(CalendarYear::pluck('name', 'id')),
                Select::make('country_id')
                    ->label('Country')->searchable()->preload()->reactive()
                    ->options(Country::pluck('name', 'id')),
                Select::make('entity_id')
                    ->label('Entity Name')->searchable()->preload()->reactive()
                    ->options(function (Get $get) {
                        if ($get('country_id')) {
                            return Entity::where('country_id', $get('country_id'))->pluck('entity_name', 'id');
                        } else {
                            return [];
                        }
                    }),
                Select::make('compliance_sub_menu_id')
                    ->label('Compliance Type')->searchable()->reactive()
                    ->options(function (callable $get) {
//                        dd($get('entity_id'));
                        if ($get('entity_id') && $get('country_id')) {

                            return ComplianceSubMenu::where('calendar_year_id',$this->calendar_year_id)->where('entity_id', $get('entity_id'))->pluck('sub_menu_name', 'id');
                        } else {
                            return [];
                        }
                    }),
                Radio::make('occurrence')->inline()->reactive()
                    ->options([
                        'Monthly' => 'Monthly',
                        'Yearly' => 'Yearly',
                        'Qtr' => 'Qtr'
                    ])->columnSpan(2),


                TableRepeater::make('event_details')->schema([
                DatePicker::make('due_date')
                    ->label('Due Date')->displayFormat('d-m-Y')
                    ->suffixIcon('heroicon-o-calendar')
                    ->closeOnDateSelection()
                    ->native(false),
                TextInput::make('event_name')
                    ->columnSpan(1)
                    ->label('Event Name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpan(1)
                    ->rows(1)
                    ->label('Description')
                    ->required(),
                Select::make('assign_id')
                    ->label('Assign to')->searchable()->preload()->reactive()
                    ->options(function (Get $get) {
                        if ($get('../../country_id')) {
                            return User::whereJsonContains('country_id', $get('../../country_id'))->role(['Compliance Officer', 'Cluster Head', 'Country Head'])->pluck('name', 'id');
                        } else {
                            return [];
                        }
                    }),

                Select::make('status')
                    ->options([
                             'Red' => 'Red',
                             'Amber' => 'Amber',
                         ])
                    ->searchable()->reactive(),

                ]),



                Placeholder::make('red')
                    ->label('')
                    ->content(new HtmlString('<h1 style="color:#fa3e3e; font-size: 15px;text-align: Left;margin-top: 36px;"> (Very Critical) </h1>'))
                    ->visible(fn(callable $get) => $get('status') === EventStatus::Red),

                Placeholder::make('amber')
                    ->label('')
                    ->content(new HtmlString('<h1 style="color:#d5b94d; font-size: 15px;text-align: Left;margin-top: 36px;"> (Event needs attention) </h1>'))
                    ->visible(fn(callable $get) => $get('status') === EventStatus::Amber),

//                Placeholder::make('green')
//                    ->label('')
//                    ->content(new HtmlString('<h1 style="color:#179a17; font-size: 15px;text-align: Left;margin-top: 36px;"> (Solved - It is a risk, but it keeps happening) </h1>'))
//                    ->visible(fn(callable $get) => $get('status') === EventStatus::Green),
//
//                Placeholder::make('blue')
//                    ->label('')
//                    ->content(new HtmlString('<h1 style="color:#73abec; font-size: 15px;text-align: Left;margin-top: 36px;"> (Event happened but its not a risk anymore) </h1>'))
//                    ->visible(fn(callable $get) => $get('status') === EventStatus::Blue),

            ])->columns(5)

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
                $compliancePrimarySubMenu->due_date = $event_detail['due_date'];
                $compliancePrimarySubMenu->event_name = $event_detail['event_name'];
                $compliancePrimarySubMenu->description = $event_detail['description'];
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
