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


    public function mount(Request $request)
    {
        $this->calendar_year_id = $request->get('calendar_year_id');
        $this->year = CalendarYear::find($this->calendar_year_id);
        $this->document_id = $request->get('document_id');
        $this->document = Document::find($this->document_id);
    }


    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\ComplianceMenu::query()->where('document_id', $this->document_id))
            ->columns([
                TextColumn::make('entity.entity_name')->label('Entity Name')
                    ->url(function (\App\Models\ComplianceMenu $record) {

                            return ComplianceSubMenuList::getUrl([
                                'compliance_menu_id' => $record->id,
                                'calendar_year_id' => $this->calendar_year_id,
                            ]);
                    })
                    ->extraAttributes(function (ComplianceMenu $record) {
                        if ($record->is_expired == 1) {
                            return [
                                'class' => 'custom-bg-green',
                            ];
                        }
                        return [];
                    }),

                TextColumn::make('updated_at')->label('Updated Date')->date('d-m-Y'),
                TextColumn::make('user.name')->label('Created By'),
            ])
            ->actions([


                \Filament\Tables\Actions\Action::make('delete')->color('danger')
                    ->icon('heroicon-o-trash')
                    ->label('Delete')
                    ->button()
                    ->requiresConfirmation()
                    ->action(function (array $data, $record, $form): void {
//                        $document = Document::whereJsonContains('entity_id',$record->id)->first();
//                       if ($document && count($document->entity_id) > 1){
//                          $entity = $document->entity_id;
//                          $newEntity =
//                       }
                        $complianceMenu = ComplianceMenu::find($record->id)->delete();
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
