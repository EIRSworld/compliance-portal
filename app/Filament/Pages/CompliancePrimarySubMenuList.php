<?php

namespace App\Filament\Pages;

use App\Models\CalendarYear;
use App\Models\ComplianceMenu;
use App\Models\CompliancePrimarySubMenu;
use App\Models\ComplianceSubMenu;
use App\Models\UploadDocument;
use Carbon\Carbon;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
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
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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
    public $compliance_menu_id, $compliance_menu;

    public function mount(Request $request)
    {
        $this->calendar_year_id = $request->get('calendar_year_id');
        $this->year = CalendarYear::find($this->calendar_year_id);
//        dd($this->year);
        $this->compliance_menu_id = $request->get('compliant_menu_id');
//dd($this->compliance_menu_id);
        $this->compliance_menu = ComplianceMenu::find($this->compliance_menu_id);
        $this->compliance_sub_menu_id = $request->get('compliant_sub_menu_id');
        $this->compliance_sub_menu = ComplianceSubMenu::find($this->compliance_sub_menu_id);
//        dd($this->compliance_sub_menu);
//        dd($this->compliance_sub_menu);
//        dd($this->compliance_menu->document_id);
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
                        'compliance_sub_menu_id' => $this->compliance_sub_menu->id,
                        'calendar_year_id' => $this->calendar_year_id,
                        'year' => $this->year->name,
                        'compliance_name' => $this->compliance_menu->name,
                        'compliance_sub_name' => $this->compliance_sub_menu->sub_menu_name,
                        'document_id' => $this->compliance_menu->document_id,
                        'document_name' => $this->compliance_menu->document->name,
                        'country_id' => $this->compliance_menu->document->country_id,
                    ]);
                })
                ->form([
                    Card::make()
                        ->schema([
                            Card::make([
                                Hidden::make('country_id'),
                                Hidden::make('document_id'),
                                Hidden::make('calendar_year_id'),
                                Hidden::make('year'),
                                TextInput::make('document_name')->label('Country')->disabled(),
                                Hidden::make('compliance_menu_id'),
                                TextInput::make('compliance_name')->label('Folder Name')->disabled(),
                                Hidden::make('compliance_sub_menu_id'),
                                TextInput::make('compliance_sub_name')->label('Sub Folder Name')->disabled(),
                                TextInput::make('primary_name')
                                    ->columnSpan(1)
                                    ->label('Task File Title')
                                    ->required(),
//                                Radio::make('folder_type')
//                                    ->label('Type')
//                                    ->options([
//                                        'Sub Folder' => 'Sub Folder',
//                                        'Upload' => 'Upload',
//                                    ])->required()
//                                    ->inline()->reactive()
//                                    ->inlineLabel(false),
                                Checkbox::make('is_expired')->label('Expired Date')->reactive(),
//                                    ->visible(function (callable $get) {
//
//                                        if ($get('folder_type') === 'Upload') {
//                                            return true;
//                                        }
//                                        return false;
//
//                                    }),

                                DatePicker::make('expired_date')
                                    ->label('Deadline')
                                    ->required()->reactive()
                                    ->suffixIcon('heroicon-o-calendar')
                                    ->closeOnDateSelection()
                                    ->native(false)
                                    ->visible(function (callable $get) {

                                        if ($get('is_expired') === true) {
                                            return true;
                                        }
                                        return false;

                                    }),
                            ])->columns(3)
                        ])
                ])
                ->action(function (array $data, $record, $form): void {
                    $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                    $compliancePrimarySubMenu->country_id = $data['country_id'];
                    $compliancePrimarySubMenu->document_id = $data['document_id'];
                    $compliancePrimarySubMenu->compliance_menu_id = $data['compliance_menu_id'];
                    $compliancePrimarySubMenu->compliance_sub_menu_id = $data['compliance_sub_menu_id'];
                    $compliancePrimarySubMenu->calendar_year_id = $data['calendar_year_id'];
                    $compliancePrimarySubMenu->year = $data['year'];
                    $compliancePrimarySubMenu->primary_name = $data['primary_name'];
                    $compliancePrimarySubMenu->folder_type = 'Upload';
                    $compliancePrimarySubMenu->is_uploaded = 0;
                    $isExpiredSet = isset($data['is_expired']) && $data['is_expired'];
                    $compliancePrimarySubMenu->is_expired = $isExpiredSet ? 1 : 0;
                    if ($compliancePrimarySubMenu->is_expired === 1) {
                        $compliancePrimarySubMenu->expired_date = $data['expired_date'];
                    }
                    $compliancePrimarySubMenu->save();

                    if ($compliancePrimarySubMenu->folder_type === 'Upload') {

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $data['country_id'];
                        $complianceUploadDocument->document_id = $data['document_id'];
                        $complianceUploadDocument->compliance_menu_id = $data['compliance_menu_id'];
                        $complianceUploadDocument->compliance_sub_menu_id = $data['compliance_sub_menu_id'];
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $data['calendar_year_id'];
                        $complianceUploadDocument->year = $data['year'];
                        $complianceUploadDocument->name = $data['primary_name'];
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->is_expired = $isExpiredSet ? 1 : 0;
                        if ($compliancePrimarySubMenu->is_expired === 1) {
                            $complianceUploadDocument->expired_date = $data['expired_date'];
                        }
                        $complianceUploadDocument->save();
                    }


                    Notification::make()
                        ->title('Folder Successfully Created')
                        ->success()
                        ->send();
//                        }


                })
                ->visible(function () {
                    if (auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Compliance Finance Manager') || auth()->user()->hasRole('Compliance Principle Manager')) {

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
            ->query(\App\Models\CompliancePrimarySubMenu::query()->where('compliance_sub_menu_id', $this->compliance_sub_menu_id)->orderBy('expired_date'))
            ->columns([
                TextColumn::make('primary_name')->label('Nasme')
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

                TextColumn::make('expired_date')->label('Due Date')->date('d-m-Y'),
                TextColumn::make('updated_at')->label('Updated Date')->date('d-m-Y'),
                TextColumn::make('user.name')->label('Created By'),
                ViewColumn::make('id')->label('Documents')->view('document.compliance-primary-sub-menu')
            ])
            ->actions([

//                ActionGroup::make([
                \Filament\Tables\Actions\Action::make('edit')->color('warning')
                    ->icon('heroicon-o-pencil')->button()
                    ->label('Edit')
                    ->mountUsing(function (ComponentContainer $form, $record) {
                        $form->fill([
                            'compliance_menu_id' => $this->compliance_menu->id,
                            'compliance_sub_menu_id' => $this->compliance_sub_menu->id,
                            'calendar_year_id' => $this->calendar_year_id,
                            'year' => $record->year,
                            'compliance_name' => $this->compliance_menu->name,
                            'compliance_sub_name' => $this->compliance_sub_menu->sub_menu_name,
                            'document_id' => $this->compliance_menu->document_id,
                            'document_name' => $this->compliance_menu->document->name,
                            'country_id' => $this->compliance_menu->document->country_id,
                            'primary_name' => $record->primary_name,
                            'expired_date' => $record->expired_date,

                        ]);
                    })
                    ->form([
                        Card::make()
                            ->schema([
                                Card::make([
                                    Hidden::make('document_id'),
                                    Hidden::make('country_id'),
                                    Hidden::make('calendar_year_id'),
                                    Hidden::make('year'),
                                    TextInput::make('document_name')->label('Country')->disabled(),
                                    Hidden::make('compliance_menu_id'),
                                    TextInput::make('compliance_name')->label('Folder Name')->disabled(),
                                    Hidden::make('compliance_sub_menu_id'),
                                    TextInput::make('compliance_sub_name')->label('Sub Folder Name')->disabled(),
                                    TextInput::make('primary_name')
                                        ->columnSpan(1)
                                        ->label('Task File Title')
                                        ->required(),
                                    DatePicker::make('expired_date')
                                        ->label('Deadline')->displayFormat('d-m-Y')
                                        ->required()
                                        ->suffixIcon('heroicon-o-calendar')
                                        ->closeOnDateSelection()
                                        ->native(false)
                                        ->visible(function (CompliancePrimarySubMenu $record) {
                                            if ($record->is_expired === 1) {
                                                return true;
                                            }

                                            return false;
                                        }),
                                ])->columns(3)
                            ])
                    ])
                    ->action(function (array $data, $record, $form): void {
                        $compliancePrimarySubMenu = CompliancePrimarySubMenu::find($record->id);
                        $compliancePrimarySubMenu->document_id = $data['document_id'];
                        $compliancePrimarySubMenu->country_id = $data['country_id'];
                        $compliancePrimarySubMenu->compliance_menu_id = $data['compliance_menu_id'];
                        $compliancePrimarySubMenu->calendar_year_id = $data['calendar_year_id'];
                        $compliancePrimarySubMenu->year = $data['year'];
                        $compliancePrimarySubMenu->primary_name = $data['primary_name'];
                        if ($record->is_expired === 1) {

                            $compliancePrimarySubMenu->expired_date = $data['expired_date'];
                        }
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = UploadDocument::whereCompliancePrimarySubMenuId($record->id)->first();
                        $complianceUploadDocument->document_id = $data['document_id'];
                        $complianceUploadDocument->country_id = $data['country_id'];
                        $complianceUploadDocument->compliance_menu_id = $data['compliance_menu_id'];
                        $complianceUploadDocument->calendar_year_id = $data['calendar_year_id'];
                        $complianceUploadDocument->year = $data['year'];
                        $complianceUploadDocument->name = $data['primary_name'];
                        if ($record->is_expired === 1) {

                            $complianceUploadDocument->expired_date = $data['expired_date'];
                        }
                        $complianceUploadDocument->save();
                        Notification::make()
                            ->title('Folder Successfully Updated')
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

                \Filament\Tables\Actions\Action::make('delete')->color('danger')
                    ->icon('heroicon-o-trash')->button()
                    ->label('Delete')
                    ->requiresConfirmation()
                    ->action(function (array $data, $record, $form): void {
                        $compliancePrimarySubMenu = CompliancePrimarySubMenu::find($record->id)->delete();
                        $complianceUploadDocument = UploadDocument::where('compliance_primary_sub_menu_id', $record->id)->delete();
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
                Action::make('upload_file')->color('success')
                    ->icon('heroicon-o-clipboard-document')->button()
                    ->label('Upload File')
//                    ->mountUsing(function (ComponentContainer $form) {
//                        $form->fill([
//                            'media' => $form->getRecord()->getMedia('upload_documents'),
//                        ]);
//                    })
                    ->form([
                        Card::make()
                            ->schema([
                                FileUpload::make('document')
                                    ->label('Upload File')
                                    ->model()
//                                    ->collection('upload_documents')
                                    ->preserveFilenames()
                                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                        return (string)str($file->getClientOriginalName())->prepend('upload_documents-');
                                    })
//                                        ->acceptedFileTypes(['application/pdf'])
                                    ->appendFiles()->required()
                                    ->downloadable()
                                    ->openable()
                                    ->multiple(),

                                Textarea::make('upload_comment')->rows(2)
                                    ->label('Comment'),
                            ])
                    ])
                    ->action(function (array $data, $record, $form): void {
                        $complianceUploadDocument = UploadDocument::where('compliance_primary_sub_menu_id', $record->id)->first();


                        foreach ($data['document'] as $document) {
                            $complianceUploadDocument->addMedia(storage_path('app/public/' . $document))->toMediaCollection('upload_documents');
                        }
                        $compliancePrimarySubMenu = \App\Models\CompliancePrimarySubMenu::find($record->id);
                        $compliancePrimarySubMenu->upload_comment = $data['upload_comment'];
                        $compliancePrimarySubMenu->is_uploaded = 1;
                        $compliancePrimarySubMenu->upload_by = auth()->id();
                        $compliancePrimarySubMenu->upload_date = Carbon::now();
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = \App\Models\UploadDocument::whereCompliancePrimarySubMenuId($record->id)->first();
                        $complianceUploadDocument->upload_comment = $data['upload_comment'];
                        $complianceUploadDocument->is_uploaded = 1;
                        $complianceUploadDocument->upload_by = auth()->id();
                        $complianceUploadDocument->upload_date = Carbon::now();
                        $complianceUploadDocument->save();
                        Notification::make()
                            ->title('File Update Successfully')
                            ->success()
                            ->send();



                    })
                    ->visible(function (CompliancePrimarySubMenu $record) {


                        if ($record->folder_type === 'Upload') {
                            return true;
                        }
                        return false;
                    })
//                    ->after()
                    ->modalWidth('md'),


//                Action::make('update_date')->color('warning')
//                    ->icon('heroicon-o-calendar')->button()
//                    ->mountUsing(function (ComponentContainer $form, $record) {
//                        $form->fill([
//                            'expired_date' => $record->expired_date,
//                        ]);
//                    })
//                    ->form([
//                        Card::make()
//                            ->schema([
//                                DatePicker::make('expired_date')
//                                    ->label('Deadline')
//                                    ->required()
//                                    ->suffixIcon('heroicon-o-calendar')
//                                    ->closeOnDateSelection()
//                                    ->native(false),
//                            ])
//                    ])
//                    ->action(function (array $data, $record, $form): void {
//                        $data = $form->getState();
//                        $complianceSubMenu = \App\Models\ComplianceSubMenu::find($record->id);
//                        $complianceSubMenu->update($data);
//                        Notification::make()
//                            ->title('Update Successfully')
//                            ->success()
//                            ->send();
//                    })
//                    ->modalWidth('sm')
//                    ->visible(function (ComplianceSubMenu $record) {
//
//                        $compliance = ComplianceMenu::where('id', $record->compliance_menu_id)->value('name');
//                        if ($compliance == 'Compliance docs with due dates') {
//                            if (auth()->user()->hasRole('Super Admin')) {
//                                return true;
//                            }
//                        }
//                        return false;
//                    }),

//                ])
            ]);
    }
}
