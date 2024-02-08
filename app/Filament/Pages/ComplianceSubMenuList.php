<?php

namespace App\Filament\Pages;

use App\Models\CalendarYear;
use App\Models\ComplianceMenu;
use App\Models\ComplianceSubMenu;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Http\Request;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ComplianceSubMenuList extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.compliance-sub-menu-list';
    protected static bool $shouldRegisterNavigation = false;

    public function getHeading(): string|Htmlable
    {
        return $this->compliance_menu->name;
    }

    protected static ?string $title = 'Compliance';
    public $compliance_menu_id, $compliance_menu,$calendar_year_id,$year;

//    protected ?string $maxContentWidth = '7xl';

    public function mount(Request $request)
    {
        $this->calendar_year_id = $request->get('calendar_year_id');
        $this->year = CalendarYear::find($this->calendar_year_id);
//        dd($this->year);
        $this->compliance_menu_id = $request->get('compliance_menu_id');
        $this->compliance_menu = ComplianceMenu::find($this->compliance_menu_id);
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('create')
//                ->label(function () {
//                    return ('Create ') . $this->compliance_menu->name .(' Sub Folder');
//                })
                ->label('New Folder')
                ->mountUsing(function (ComponentContainer $form) {
                    $form->fill([
                        'compliance_menu_id' => $this->compliance_menu->id,
                        'calendar_year_id' => $this->calendar_year_id,
                        'year' => $this->year->name,
                        'compliance_name' => $this->compliance_menu->name,
                        'document_id' => $this->compliance_menu->document_id,
                        'document_name' => $this->compliance_menu->document->name,
                    ]);
                })
                ->form([
                    Card::make()
                        ->schema([
                            Card::make([
                                Hidden::make('document_id'),
                                Hidden::make('calendar_year_id'),
                                Hidden::make('year'),
                                TextInput::make('document_name')->label('Country')->disabled(),
                                Hidden::make('compliance_menu_id'),
                                TextInput::make('compliance_name')->label('Folder Name')->disabled(),
                                TextInput::make('name')
                                    ->columnSpan(1)
                                    ->label('Task File Title')
                                    ->required(),
                                DatePicker::make('expired_date')
                                    ->label('Deadline')
                                    ->required()
                                    ->suffixIcon('heroicon-o-calendar')
                                    ->closeOnDateSelection()
                                    ->native(false)
                                    ->visible(function () {
                                        if ($this->compliance_menu->name == 'Compliance docs with due dates') {
                                            if (auth()->user()->hasRole('compliance_manager') || auth()->user()->hasRole('super_admin')) {
                                                return true;
                                            }
                                        }
                                        return false;
                                    }),
                            ])->columns(3)
                        ])
                ])
                ->action(function (array $data, $record, $form): void {
                    $complianceSubMenu = new ComplianceSubMenu();
                    $complianceSubMenu->document_id = $data['document_id'];
                    $complianceSubMenu->compliance_menu_id = $data['compliance_menu_id'];
                    $complianceSubMenu->calendar_year_id = $data['calendar_year_id'];
                    $complianceSubMenu->year = $data['year'];
                    $complianceSubMenu->name = $data['name'];
                    if ($this->compliance_menu->name === 'Compliance docs with due dates') {
                        $complianceSubMenu->expired_date = $data['expired_date'];
                    }

                    $complianceSubMenu->is_uploaded = 0;
                    $complianceSubMenu->save();

                    Notification::make()
                        ->title('Folder Successfully Created')
                        ->success()
                        ->send();
//                        }


                })
                ->visible(function () {

                    if (auth()->user()->hasRole('compliance_manager') || auth()->user()->hasRole('super_admin')) {
                        return true;
                    }
                    return false;
                })
//                ->url(ComplianceSubMenuCreate::getUrl(['compliance_menu_id' => $this->compliance_menu_id]))
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\ComplianceSubMenu::query()->where('compliance_menu_id', $this->compliance_menu_id)->orderBy('expired_date'))
            ->columns([
                TextColumn::make('name')->label('Name')
                    ->url(fn(\App\Models\ComplianceSubMenu $record): string => CompliantView::getUrl([
                        'compliant_sub_menu_id' => $record->id,
                        'calendar_year_id' => $record->calendar_year_id,

                        ])),
                TextColumn::make('updated_at')->label('Updated Date')->date('d-m-Y'),
                TextColumn::make('expired_date')->label('Expired Date')->date('d-m-Y')
                    ->visible(function () {
                        if ($this->compliance_menu->name == 'Compliance docs with due dates') {
                            if (auth()->user()->hasRole('compliance_manager') || auth()->user()->hasRole('super_admin')) {
                                return true;
                            }
                        }
                        return false;
                    }),
                TextColumn::make('user.name')->label('Created By'),
            ])
            ->actions([

//                ActionGroup::make([
                \Filament\Tables\Actions\Action::make('edit')->color('warning')
                    ->icon('heroicon-o-pencil')->button()
                    ->label('Edit')
                    ->mountUsing(function (ComponentContainer $form, $record) {
                        $form->fill([
                            'compliance_menu_id' => $this->compliance_menu->id,
                            'calendar_year_id' => $this->calendar_year_id,
                            'year' => $record->year,
                            'compliance_name' => $this->compliance_menu->name,
                            'document_id' => $this->compliance_menu->document_id,
                            'document_name' => $this->compliance_menu->document->name,
                            'name' => $record->name,

                        ]);
                    })
                    ->form([
                        Card::make()
                            ->schema([
                                Card::make([
                                    Hidden::make('document_id'),
                                    Hidden::make('calendar_year_id'),
                                    Hidden::make('year'),
                                    TextInput::make('document_name')->label('Country')->disabled(),
                                    Hidden::make('compliance_menu_id'),
                                    TextInput::make('compliance_name')->label('Folder Name')->disabled(),
                                    TextInput::make('name')
                                        ->columnSpan(1)
                                        ->label('Task File Title')
                                        ->required(),
                                    DatePicker::make('expired_date')
                                        ->label('Deadline')
                                        ->required()
                                        ->suffixIcon('heroicon-o-calendar')
                                        ->closeOnDateSelection()
                                        ->native(false)
                                        ->visible(function () {
                                            if ($this->compliance_menu->name == 'Compliance docs with due dates') {
                                                if (auth()->user()->hasRole('compliance_manager') || auth()->user()->hasRole('super_admin')) {
                                                    return true;
                                                }
                                            }
                                            return false;
                                        }),
                                ])->columns(3)
                            ])
                    ])
                    ->action(function (array $data, $record, $form): void {
                        $complianceSubMenu = ComplianceSubMenu::find($record->id);
                        $complianceSubMenu->document_id = $data['document_id'];
                        $complianceSubMenu->compliance_menu_id = $data['compliance_menu_id'];
                        $complianceSubMenu->calendar_year_id = $data['calendar_year_id'];
                        $complianceSubMenu->year = $data['year'];
                        $complianceSubMenu->name = $data['name'];
                        $complianceSubMenu->save();
                        Notification::make()
                            ->title('Folder Successfully Updated')
                            ->success()
                            ->send();
//                        }


                    })
                    ->visible(function () {

                        if (auth()->user()->hasRole('compliance_manager') || auth()->user()->hasRole('super_admin')) {
                            return true;
                        }
                        return false;
                    }),

                \Filament\Tables\Actions\Action::make('delete')->color('danger')
                    ->icon('heroicon-o-trash')->button()
                    ->label('Delete')
                    ->requiresConfirmation()
                    ->action(function (array $data, $record, $form): void {
                        $complianceSubMenu = ComplianceSubMenu::find($record->id)->delete();
                        Notification::make()
                            ->title('Deleted Successfully')
                            ->success()
                            ->send();
//                        }


                    })->visible(function () {

                        if (auth()->user()->hasRole('compliance_manager') || auth()->user()->hasRole('super_admin')) {
                            return true;
                        }
                        return false;
                    }),
                Action::make('upload_file')->color('success')
                    ->icon('heroicon-o-clipboard-document')->button()
                    ->label('Upload File')
                    ->mountUsing(function (ComponentContainer $form) {
                        $form->fill([
                            'media' => $form->getRecord()->getMedia('compliant_attachments'),
                        ]);
                    })
                    ->form([
                        Card::make()
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('media')
                                    ->label('Upload File')
                                    ->model()
                                    ->collection('compliant_attachments')
                                    ->preserveFilenames()
                                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                        return (string)str($file->getClientOriginalName())->prepend('compliant_attachments-');
                                    })
//                                        ->acceptedFileTypes(['application/pdf'])
                                    ->appendFiles()
                                    ->downloadable()
                                    ->openable()
                                    ->multiple(),
                            ])
                    ])
                    ->action(function (array $data, $record, $form): void {

                        $mediaFile = Media::whereModelType('App\Models\ComplianceSubMenu')->whereModelId($record->id)->whereCollectionName('compliant_attachments')->get();

                        if ($mediaFile) {
                            $complianceSubMenu = \App\Models\ComplianceSubMenu::find($record->id);
                            $complianceSubMenu->is_uploaded = 1;
                            $complianceSubMenu->save();
                            Notification::make()
                                ->title('File Update Successfully')
                                ->success()
                                ->send();
                        }


                    })
//                    ->after()
                    ->modalWidth('md'),


                Action::make('update_date')->color('warning')
                    ->icon('heroicon-o-calendar')->button()
                    ->mountUsing(function (ComponentContainer $form, $record) {
                        $form->fill([
                            'expired_date' => $record->expired_date,
                        ]);
                    })
                    ->form([
                        Card::make()
                            ->schema([
                                DatePicker::make('expired_date')
                                    ->label('Deadline')
                                    ->required()
                                    ->suffixIcon('heroicon-o-calendar')
                                    ->closeOnDateSelection()
                                    ->native(false),
                            ])
                    ])
                    ->action(function (array $data, $record, $form): void {
                        $data = $form->getState();
                        $complianceSubMenu = \App\Models\ComplianceSubMenu::find($record->id);
                        $complianceSubMenu->update($data);
                        Notification::make()
                            ->title('Update Successfully')
                            ->success()
                            ->send();
                    })
                    ->modalWidth('sm')
                    ->visible(function (ComplianceSubMenu $record) {

                        $compliance = ComplianceMenu::where('id', $record->compliance_menu_id)->value('name');
                        if ($compliance == 'Compliance docs with due dates') {
                            if (auth()->user()->hasRole('compliance_manager') || auth()->user()->hasRole('super_admin')) {
                                return true;
                            }
                        }
                        return false;
                    }),

//                ])
            ]);
    }
}
