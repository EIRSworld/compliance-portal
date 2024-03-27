<?php

namespace App\Filament\Pages;

use App\Models\CalendarYear;
use App\Models\ComplianceMenu;
use App\Models\CompliancePrimarySubMenu;
use App\Models\ComplianceSubMenu;
use App\Models\Country;
use App\Models\Document;
use App\Models\Lead;
use App\Models\UploadDocument;
use App\Models\User;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Http\Request;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ComplianceMenuList extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.compliance-menu-list';

    public function getHeading(): string|Htmlable
    {
        return $this->document->name;
    }

    protected static bool $shouldRegisterNavigation = false;


    public $document_id, $document, $calendar_year_id, $year, $user_document;

//    protected ?string $maxContentWidth = '7xl';

    public function mount(Request $request)
    {
        $this->calendar_year_id = $request->get('calendar_year_id');
        $this->year = CalendarYear::find($this->calendar_year_id);
        $this->document_id = $request->get('document_id');
//        dd($this->calendar_year_id);
        $this->document = Document::find($this->document_id);
    }
//    public function getBreadcrumbs(): array
//    {
//        return [$this->country->name];
//    }
    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->modalWidth('md')
                ->label('New Folder')
                ->mountUsing(function (ComponentContainer $form) {
                    $form->fill([
                        'document_id' => $this->document_id,
                        'calendar_year_id' => $this->calendar_year_id,
                        'year' => $this->year->name,
//                        'country_id' => $this->document->country_id,
                        'document_name' => $this->document->name,

                    ]);
                })
                ->form([
                    Card::make()
                        ->schema([
                            Hidden::make('document_id'),
                            Hidden::make('calendar_year_id'),
//                                Hidden::make('country_id'),
                            Hidden::make('year'),
                            TextInput::make('document_name')->label('Country')->disabled()->columnSpan(1),
                            TextInput::make('name')
                                ->columnSpan(1)
                                ->label('Name')
                                ->required(),
                            Radio::make('folder_type')
                                ->label('Type')
                                ->options([
                                    'Sub Folder' => 'Sub Folder',
                                    'Upload' => 'Upload',
                                ])->required()
                                ->inline()->reactive()
                                ->inlineLabel(false),
                            Checkbox::make('is_expired')->label('Expired Date')->reactive()
                                ->visible(function (callable $get) {

                                    if ($get('folder_type') === 'Upload') {
                                        return true;
                                    }
                                    return false;

                                }),

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

                        ])->columns(1),
                ])
                ->action(function (array $data, $record, $form): void {

                    $complianceMenu = new ComplianceMenu();
                    $complianceMenu->document_id = $data['document_id'];
                    $complianceMenu->calendar_year_id = $this->calendar_year_id;
                    $complianceMenu->year = $data['year'];
                    $complianceMenu->country_id = $this->document->country_id;
                    $complianceMenu->name = $data['name'];
                    $complianceMenu->folder_type = $data['folder_type'];
                    $isExpiredSet = isset($data['is_expired']) && $data['is_expired'];
                    $complianceMenu->is_expired = $isExpiredSet ? 1 : 0;
                    if ($complianceMenu->is_expired === 1) {
                        $complianceMenu->expired_date = $data['expired_date'];
                    }
                    $complianceMenu->save();

                    if ($complianceMenu->folder_type === 'Upload') {

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $this->document->country_id;
                        $complianceUploadDocument->document_id = $data['document_id'];
                        $complianceUploadDocument->compliance_menu_id = $complianceMenu->id;
                        $complianceUploadDocument->calendar_year_id = $this->calendar_year_id;
                        $complianceUploadDocument->year = $data['year'];
                        $complianceUploadDocument->name = $data['name'];
                        $complianceUploadDocument->folder_type = $data['folder_type'];
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->is_expired = $isExpiredSet ? 1 : 0;
                        if ($complianceMenu->is_expired === 1) {
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
//                ->url(ComplianceMenuCreate::getUrl(['country_id' => $this->country_id]))
        ];
    }

    public function table(Table $table): Table
    {
        $user = User::whereId(auth()->user()->id)->first();
        return $table
            ->query(\App\Models\ComplianceMenu::query()->where('document_id', $this->document_id)->whereIn('name',$user->menu_access))
            ->columns([
                TextColumn::make('name')->label('Name')
                    ->url(function (\App\Models\ComplianceMenu $record) {
                        if ($record->folder_type == 'Sub Folder') {

                            return ComplianceSubMenuList::getUrl([
                                'compliance_menu_id' => $record->id,
                                'calendar_year_id' => $this->calendar_year_id,
                            ]);
                        } else {
                            return null;
                        }
                    })
                    ->extraAttributes(function (ComplianceMenu $record) {
                        if ($record->is_expired == 1) {
                            return [
                                'class' => 'custom-bg-green',
                            ];
                        }
                        return [];
                    }),
                TextColumn::make('expired_date')->label('Due Date')->date('d-m-Y'),
//                ->getStateUsing(function (ComplianceMenu $record){
//                    if ($record->expired_date){
//                        return $record->expired_date;
//                    }
//                    return '-';
//                }),
                TextColumn::make('updated_at')->label('Updated Date')->date('d-m-Y'),
                TextColumn::make('user.name')->label('Created By'),
                ViewColumn::make('id')->label('Documents')->view('document.compliance-menu')
            ])
            ->actions([
                \Filament\Tables\Actions\Action::make('edit')->color('warning')->button()
                    ->icon('heroicon-o-pencil')->modalWidth('md')
                    ->label('Edit')
                    ->mountUsing(function (ComponentContainer $form, $record) {
                        $form->fill([
                            'document_id' => $this->document_id,
                            'calendar_year_id' => $record->calendar_year_id,
                            'document_name' => $this->document->name,
                            'name' => $record->name,
                            'year' => $record->year,
                            'folder_type' => $record->folder_type,
                            'expired_date' => $record->expired_date,
                        ]);
                    })
                    ->form([
                        Card::make()
                            ->schema([
                                Card::make([
                                    Hidden::make('document_id'),
                                    Hidden::make('calendar_year_id'),
                                    Hidden::make('year'),
                                    TextInput::make('document_name')->label('Country')->disabled()->columnSpan(1),
                                    TextInput::make('name')
                                        ->columnSpan(1)
                                        ->label('Name')
                                        ->required(),
                                    DatePicker::make('expired_date')
                                        ->label('Deadline')->displayFormat('d-m-Y')
                                        ->required()
                                        ->suffixIcon('heroicon-o-calendar')
                                        ->closeOnDateSelection()
                                        ->native(false)
                                        ->visible(function (ComplianceMenu $record) {
                                            if ($record->is_expired === 1) {
                                                return true;
                                            }

                                            return false;
                                        }),
                                ])->columns(1)
                            ])
                    ])
                    ->action(function (array $data, $record, $form): void {
                        $complianceMenu = ComplianceMenu::find($record->id);
                        $complianceMenu->document_id = $data['document_id'];
                        $complianceMenu->calendar_year_id = $data['calendar_year_id'];
                        $complianceMenu->year = $data['year'];
                        $complianceMenu->name = $data['name'];

                        if ($record->is_expired === 1) {
                            $complianceMenu->expired_date = $data['expired_date'];
                        }
                        $complianceMenu->save();

                        $complianceUploadDocument = UploadDocument::whereComplianceMenuId($record->id)->first();
                        $complianceUploadDocument->document_id = $data['document_id'];
                        $complianceUploadDocument->calendar_year_id = $data['calendar_year_id'];
                        $complianceUploadDocument->year = $data['year'];
                        $complianceUploadDocument->name = $data['name'];

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
                    ->icon('heroicon-o-trash')
                    ->label('Delete')
                    ->button()
                    ->requiresConfirmation()
                    ->action(function (array $data, $record, $form): void {
//                        dd($record);
                        $complianceMenu = ComplianceMenu::find($record->id)->delete();
                        $complianceSubMenu = ComplianceSubMenu::where('compliance_menu_id', $record->id)->delete();
                        $compliancePrimarySubMenu = CompliancePrimarySubMenu::where('compliance_menu_id', $record->id)->delete();
                        $complianceUploadDocument = UploadDocument::where('compliance_menu_id', $record->id)->delete();
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
                \Filament\Tables\Actions\Action::make('upload_file')->color('success')
                    ->icon('heroicon-o-clipboard-document')->button()
                    ->label('Upload File')
//                    ->mountUsing(function (ComponentContainer $form) {
//                        $form->fill([
//                            'media' => $form->getRecord()->getMedia('compliant_documents'),
//                        ]);
//                    })
                    ->form([
                        Card::make()
                            ->schema([
                                FileUpload::make('document')
                                    ->label('Upload File')
                                    ->model()
//                                    ->collection('compliant_documents')
                                    ->preserveFilenames()
                                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                        return (string)str($file->getClientOriginalName())->prepend('compliant_attachments-');
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

                        $complianceUploadDocument = UploadDocument::where('compliance_menu_id', $record->id)->first();

                        foreach ($data['document'] as $document) {
                            $complianceUploadDocument->addMedia(storage_path('app/public/' . $document))->toMediaCollection('upload_documents');
                        }
                        $complianceMenu = \App\Models\ComplianceMenu::find($record->id);
                        $complianceMenu->upload_comment = $data['upload_comment'];
                        $complianceMenu->is_uploaded = 1;
                        $complianceMenu->upload_by = auth()->id();
                        $complianceMenu->upload_date = Carbon::now();
                        $complianceMenu->save();

                        $complianceUploadDocument = \App\Models\UploadDocument::whereComplianceMenuId($record->id)->first();
                        $complianceUploadDocument->upload_comment = $data['upload_comment'];
                        $complianceUploadDocument->is_uploaded = 1;
                        $complianceUploadDocument->upload_by = auth()->id();
                        $complianceUploadDocument->upload_date = Carbon::now();
                        $complianceUploadDocument->save();
                        Notification::make()
                            ->title('File Update Successfully')
                            ->success()
                            ->send();

//                        $mediaFile = Media::whereModelType('App\Models\ComplianceMenu')->whereModelId($record->id)->whereCollectionName('compliant_documents')->get();
//
//                        if ($mediaFile) {
//                            $complianceSubMenu = \App\Models\ComplianceMenu::find($record->id);
//                            $complianceSubMenu->is_uploaded = 1;
//                            $complianceSubMenu->save();
//                            Notification::make()
//                                ->title('File Update Successfully')
//                                ->success()
//                                ->send();
//                        }


                    })
                    ->visible(function (ComplianceMenu $record) {


                        if ($record->folder_type === 'Upload') {
                            return true;
                        }
                        return false;
                    })
//                    ->after()
                    ->modalWidth('md'),
            ], position: ActionsPosition::AfterColumns);
    }
}
