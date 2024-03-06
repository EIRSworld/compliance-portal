<?php

namespace App\Filament\Pages;

use App\Enums\EventStatus;
use App\Models\CalendarYear;
use App\Models\ComplianceEvent;
use App\Models\Country;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
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

    public $calendar_year_id, $country_id, $name, $description, $status, $status_text;

    public function mount()
    {

        $currentYear = Carbon::now()->year;

        $calendarYear = CalendarYear::where('name', $currentYear)->value('id');
        $this->calendar_year_id = $calendarYear;


    }

    protected function getFormModel(): Model|string|null
    {
        return ComplianceEvent::class;
    }

    protected function getFormSchema(): array
    {
        return [

            Card::make([
                Select::make('calendar_year_id')
                    ->label('Calendar Year')->searchable()->preload()
                    ->options(CalendarYear::pluck('name', 'id')),
//                    ->default(function(){
//                        $currentYear = Carbon::now()->year;
//                        return CalendarYear::where('name', $currentYear)->value('id');
//                    }),
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
                    ->content(new HtmlString('<h1 style="color:#73abec; font-size: 15px;text-align: Left;margin-top: 36px;"> (Event happened but its not a risk anymore) </h1>'))
                    ->visible(function (callable $get) {
                        if ($get('status') === EventStatus::Blue) {
                            return true;
                        }
                        return false;

                    }),
            ])->columns(3)

        ];
    }

    public function submit()
    {
        try {
            DB::beginTransaction();

            $complianceEvent = new ComplianceEvent();
            $complianceEvent->calendar_year_id = $this->calendar_year_id;
            $complianceEvent->country_id = $this->country_id;
            $complianceEvent->name = $this->name;
            $complianceEvent->description = $this->description;
            $complianceEvent->status = $this->status;
            $complianceEvent->save();

            DB::commit();

            Notification::make()
                ->title('Event Successfully Created')
                ->success()
                ->send();

            if ($this->status == 'Red') {

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


            return redirect(ComplianceEventSummary::getUrl());
        } catch (\Exception $exception) {
            Log::emergency("Exception while adding Folder " . $exception->getMessage() . " on line " . $exception->getLine());

            Notification::make()
                ->title($exception->getMessage())
                ->danger()
                ->send();
        }
    }
}
