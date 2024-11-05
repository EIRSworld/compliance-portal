<?php

namespace App\Filament\Pages;

use App\Enums\EventStatus;
use App\Models\CalendarYear;
use App\Models\ComplianceMenu;
use App\Models\CompliancePrimarySubMenu;
use App\Models\ComplianceSubMenu;
use App\Models\Country;
use App\Models\UploadDocument;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use function Symfony\Component\String\s;

class CompliancePrimarySubMenuList extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.compliance-primary-sub-menu-list';
    protected static bool $shouldRegisterNavigation = false;


    public function getHeading(): string|Htmlable
    {
        return $this->compliance_sub_menu->sub_menu_name;
    }

    public $compliance_sub_menu_id, $compliance_sub_menu, $calendar_year_id, $year;
    public $compliance_menu_id, $compliance_menu,$occurrence,$due_date,$event_name,$description,$assign_id,$status;

    public function mount(Request $request)
    {
        $this->calendar_year_id = $request->get('calendar_year_id');
        $this->year = CalendarYear::find($this->calendar_year_id);

        $this->compliance_menu_id = $request->get('compliant_menu_id');

        $this->compliance_menu = ComplianceMenu::find($this->compliance_menu_id);
        $this->compliance_sub_menu_id = $request->get('compliant_sub_menu_id');
        $this->compliance_sub_menu = ComplianceSubMenu::find($this->compliance_sub_menu_id);
//dd($this->calendar_year_id,$this->compliance_sub_menu);
    }


//    protected function getHeaderActions(): array
//    {
//        return [
//            \Filament\Actions\Action::make('create')->modalWidth('7xl')
//                ->label('New Event')
//                ->mountUsing(function (ComponentContainer $form) {
//                    $form->fill([
//                        'compliance_menu_id' => $this->compliance_menu->id,
//                        'compliance_sub_menu_id' => $this->compliance_sub_menu->id,
//                        'calendar_year_id' => $this->calendar_year_id,
//                        'year' => $this->year->name,
//                        'compliance_name' => $this->compliance_menu->name,
//                        'compliance_sub_name' => $this->compliance_sub_menu->sub_menu_name,
//                        'document_id' => $this->compliance_menu->document_id,
//                        'document_name' => $this->compliance_menu->document->name,
//                        'country_id' => $this->compliance_menu->country_id,
//                        'entity_id' => $this->compliance_menu->entity_id,
//                        'entity_name' => $this->compliance_menu->entity->entity_name,
//                    ]);
//                })
//                ->form([
//                    Card::make()
//                        ->schema([
//                            Card::make([
//                                Hidden::make('country_id'),
//                                Hidden::make('entity_id'),
//                                Hidden::make('document_id'),
//                                Hidden::make('calendar_year_id'),
//                                Hidden::make('year'),
//                                TextInput::make('document_name')->label('Country')->disabled(),
//                                Hidden::make('compliance_menu_id'),
//                                TextInput::make('entity_name')->label('Entity Name')->disabled(),
//                                Hidden::make('compliance_sub_menu_id'),
//                                TextInput::make('compliance_sub_name')->label('Compliance Type')->disabled(),
//                                Radio::make('occurrence')->inline()
//                                    ->options([
//                                        'Monthly' => 'Monthly',
//                                        'Yearly' => 'Yearly',
//                                        'Qtr' => 'Qtr'
//                                    ]),
//                                DatePicker::make('due_date')
//                                    ->label('Due Date')->displayFormat('d-m-Y')
//                                    ->suffixIcon('heroicon-o-calendar')
//                                    ->closeOnDateSelection()
//                                    ->native(false),
//                                Select::make('assign_id')
//                                    ->label('Assign to')->searchable()->preload()->reactive()
//                                    ->options(function () {
//                                        return User::whereJsonContains('country_id', $this->compliance_menu->country_id)->role(['Compliance Officer', 'Cluster Head', 'Country Head'])->pluck('name', 'id');
//                                    }),
//                                TextInput::make('event_name')
//                                    ->columnSpan(1)
//                                    ->label('Event Name')
//                                    ->required(),
//                                Textarea::make('description')
//                                    ->columnSpan(1)
//                                    ->label('Description')
//                                    ->required(),
//
//                                Select::make('status')
//                                    ->options([
//                                        'Red' => 'Red',
//                                        'Amber' => 'Amber',
//                                    ])
//                                    ->searchable()->reactive(),
//                                Placeholder::make('red')
//                                    ->label('')
//                                    ->content(new HtmlString('<h1 style="color:#fa3e3e; font-size: 15px;text-align: Left;margin-top: 36px;"> (Very Critical) </h1>'))
//                                    ->visible(fn(callable $get) => $get('status') === EventStatus::Red),
//
//                                Placeholder::make('amber')
//                                    ->label('')
//                                    ->content(new HtmlString('<h1 style="color:#d5b94d; font-size: 15px;text-align: Left;margin-top: 36px;"> (Event needs attention) </h1>'))
//                                    ->visible(fn(callable $get) => $get('status') === EventStatus::Amber),
//
//                            ])->columns(3)
//                        ])
//                ])
//                ->action(function (array $data, $record, $form): void {
//
//                    $complianceSubMenu = ComplianceSubMenu::find((int)$this->compliance_sub_menu_id);
//                    $calendarYear = CalendarYear::find($this->calendar_year_id);
//
//                    $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
//                    $compliancePrimarySubMenu->calendar_year_id = $this->calendar_year_id;
//                    $compliancePrimarySubMenu->year = $calendarYear->name;
//                    $compliancePrimarySubMenu->country_id =  $this->compliance_menu->country_id;
//                    $compliancePrimarySubMenu->entity_id = $this->compliance_menu->entity_id;
//                    $compliancePrimarySubMenu->document_id = $complianceSubMenu->document_id;
//                    $compliancePrimarySubMenu->compliance_menu_id = $complianceSubMenu->compliance_menu_id;
//                    $compliancePrimarySubMenu->compliance_sub_menu_id = $this->compliance_sub_menu->id;
//                    $compliancePrimarySubMenu->occurrence = $data['occurrence'];
//                    $compliancePrimarySubMenu->due_date = $data['due_date'];
//                    $compliancePrimarySubMenu->event_name = $data['event_name'];
//                    $compliancePrimarySubMenu->description = $data['description'];
//                    $compliancePrimarySubMenu->assign_id = $data['assign_id'];
//                    $compliancePrimarySubMenu->status = $data['status'];
//                    $compliancePrimarySubMenu->save();
//
//
//
//                    Notification::make()
//                        ->title('Folder Successfully Created')
//                        ->success()
//                        ->send();
////                        }
//
//
//                })
//                ->visible(function () {
//                    if (auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Compliance Finance Manager') || auth()->user()->hasRole('Compliance Principle Manager')) {
//
//                        return true;
//                    }
//                    return false;
//                })
////                ->url(ComplianceSubMenuCreate::getUrl(['compliance_menu_id' => $this->compliance_menu_id]))
//        ];
//    }

    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\CompliancePrimarySubMenu::query()->where('compliance_sub_menu_id', $this->compliance_sub_menu_id))
            ->columns([
                TextColumn::make('event_name')->label('Event Name')
                    ->url(fn(\App\Models\CompliancePrimarySubMenu $record): string => CompliantView::getUrl([
                        'compliant_primary_sub_menu_id' => $record->id,
                        'calendar_year_id' => $record->calendar_year_id,

                    ]))->openUrlInNewTab()
//                    ->url(fn(\App\Models\CompliancePrimarySubMenu $record): string => ComplianceSecondarySubMenuList::getUrl([
//                        'compliant_primary_sub_menu_id' => $record->id,
//                        'calendar_year_id' => $record->calendar_year_id,
//
//                    ]))
                    ->extraAttributes(function (CompliancePrimarySubMenu $record) {
                        if ($record->is_expired == 1) {
                            return [
                                'class' => 'custom-bg-green',
                            ];
                        }
                        return [];
                    }),

//                TextColumn::make('expired_date')->label('Due Date')->date('d-m-Y'),
                TextColumn::make('due_date')->label('Due Date')->date('d-m-Y'),
                TextColumn::make('event_type')->label('Event Type'),
                TextColumn::make('occurrence')->label('Occurrence'),
                TextColumn::make('user.name')->label('Created By'),
                ViewColumn::make('id')->label('Documents')->view('document.compliance-primary-sub-menu'),
                ViewColumn::make('ids')->label('Email Documents')->view('document.compliance-mail-document'),
            ])
            ->actions([

                Action::make('upload_file')->color('success')
                    ->icon('heroicon-o-clipboard-document')->button()
                    ->label('Upload File')
                    ->mountUsing(function (ComponentContainer $form, CompliancePrimarySubMenu $record) {
                        $form->fill([
                            'media' => $form->getRecord()->getMedia('compliance_primary_attachments'),
                            'mail_media' => $form->getRecord()->getMedia('mail_document'),
                            'upload_comment' => $record->upload_comment,
                            'subject' => $record->subject,
                        ]);
                    })
                    ->form([
                        Card::make()
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('media')
                                    ->label('Upload File')
                                    ->model()
                                    ->collection('compliance_primary_attachments')
                                    ->preserveFilenames()
                                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                        return (string)str($file->getClientOriginalName())->prepend('compliance_primary_attachments-');
                                    })
//                                        ->acceptedFileTypes(['application/pdf'])
                                    ->appendFiles()
                                    ->downloadable()
                                    ->openable()
                                    ->required()
                                    ->multiple(),
                                Textarea::make('upload_comment')->rows(2)
                                    ->label('Comment'),

//                                TextInput::make('subject')->label('Subject'),
                                SpatieMediaLibraryFileUpload::make('mail_media')
                                    ->label('Upload Email File')
                                    ->model()
                                    ->collection('mail_document')
                                    ->preserveFilenames()
                                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                        return (string)str($file->getClientOriginalName())->prepend('mail_document-');
                                    })
//                                        ->acceptedFileTypes(['application/pdf'])
                                    ->appendFiles()
                                    ->downloadable()
                                    ->openable(),
                            ])
                    ])
                    ->action(function (array $data, $record, $form): void {

                        $mediaFile = Media::whereModelType('App\Models\CompliancePrimarySubMenu')->whereModelId($record->id)->whereCollectionName('compliance_primary_attachments')->get();

                        if ($mediaFile) {
                            $compliancePrimarySubMenu = \App\Models\CompliancePrimarySubMenu::find($record->id);
                            if($compliancePrimarySubMenu->is_uploaded == 0) {
//                                $compliancePrimarySubMenu->subject = $data['subject'];
                                $compliancePrimarySubMenu->upload_comment = $data['upload_comment'];
                                $compliancePrimarySubMenu->is_uploaded = 1;
                                $compliancePrimarySubMenu->upload_by = auth()->user()->id;
                                $compliancePrimarySubMenu->upload_date = Carbon::now()->format('Y-m-d');
                                $compliancePrimarySubMenu->status = 'Green';
                                $compliancePrimarySubMenu->save();
                                Notification::make()
                                    ->title('File Update Successfully')
                                    ->success()
                                    ->send();
                            }
                        }


                    })
                    ->visible(function (CompliancePrimarySubMenu $record) {

                        if ($record->approve_status != 1) {
                            return true;
                        }
                        return false;
                    })
                    ->modalWidth('md'),


//                ActionGroup::make([
                \Filament\Tables\Actions\Action::make('edit')->color('warning')
                    ->icon('heroicon-o-pencil')->button()
                    ->label('Edit')
                    ->mountUsing(function (ComponentContainer $form, $record) {
                        $form->fill([
                            'event_name' => $record->event_name,
                            'due_date' => $record->due_date,
                            'status' => $record->status,
                            'assign_id' => $record->assign_id,
                            'country_id' => $record->country_id,

                        ]);
                    })
                    ->form([
                        Card::make()
                            ->schema([
                                Card::make([
                                    Hidden::make('country_id')->reactive(),
                                    TextInput::make('event_name')
//                                        ->columnSpan(1)
                                        ->label('Event Name')
                                        ->required(),
                                    DatePicker::make('due_date')
                                        ->label('Due Date')->displayFormat('d-m-Y')
                                        ->required()->reactive()
                                        ->suffixIcon('heroicon-o-calendar')
                                        ->closeOnDateSelection()
                                        ->native(false)
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
                                                return ['style' => 'background:#fcc4c4;'];
                                            } elseif ($get('status') == EventStatus::Amber) {
                                                return ['style' => 'background:#ffeec7;'];
                                            }
                                            return [];
                                        }),
                                    Select::make('status')
                                        ->columnSpan(1)
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
                                                return ['style' => 'background:#fcc4c4;'];
                                            } elseif ($get('status') == EventStatus::Amber) {
                                                return ['style' => 'background:#ffeec7;'];
                                            }
                                            return [];
                                        }),
                                    Select::make('assign_id')
                                        ->label('Assign to')->searchable()->preload()->reactive()
                                        ->options(function (Get $get) {
                                            if ($get('country_id')) {
//                                                $country = Country::whereName($get('country_id'))->first();
                                            return User::role(['Compliance Officer', 'Cluster Head', 'Country Head'])->
                                                whereJsonContains('country_id', (string)$get('country_id'))->pluck('name', 'id');
//                                                return User::
//                                                whereJsonContains('country_id', $get('country_id'))->
//                                                pluck('name', 'id');
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

                                ])->columns(3)
                            ])
                    ])
                    ->action(function (array $data, $record, $form): void {
                        $compliancePrimarySubMenu = CompliancePrimarySubMenu::find($record->id);
                        $compliancePrimarySubMenu->event_name = $data['event_name'];
                        $compliancePrimarySubMenu->due_date = $data['due_date'];
                        $compliancePrimarySubMenu->assign_id = $data['assign_id'];
                        $compliancePrimarySubMenu->status = $data['status'];

                        $compliancePrimarySubMenu->save();

                        Notification::make()
                            ->title('Event Update Successfully')
                            ->success()
                            ->send();
//                        }


                    })
                    ->visible(function () {
//                        return false;
                        if (auth()->user()->hasRole('Super Admin'))
//                            || auth()->user()->hasRole('Compliance Finance Manager') || auth()->user()->hasRole('Compliance Principle Manager'))
                        {

                            return true;
                        }
                        return false;
                    }),

                \Filament\Tables\Actions\Action::make('delete')->color('danger')
                    ->icon('heroicon-o-trash')->button()
                    ->label('Delete')
                    ->requiresConfirmation()
                    ->action(function (array $data, $record, $form): void {
                        $compliancePrimarySubMenu = CompliancePrimarySubMenu::find($record->id)->delete();

                        Notification::make()
                            ->title('Deleted Successfully')
                            ->success()
                            ->send();
//                        }


                    })
                    ->visible(function () {
                        if (auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Compliance Finance Manager') || auth()->user()->hasRole('Compliance Principle Manager')) {

                            return true;
                        }
                        return false;
                    }),

            ]);
    }
}
