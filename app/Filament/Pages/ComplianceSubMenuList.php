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
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
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
        return $this->compliance_menu->entity->entity_name;
    }

    public $compliance_menu_id, $compliance_menu, $calendar_year_id, $year;


    public function mount(Request $request)
    {
        $this->calendar_year_id = $request->get('calendar_year_id');
        $this->year = CalendarYear::find($this->calendar_year_id);
        $this->compliance_menu_id = $request->get('compliance_menu_id');
        $this->compliance_menu = ComplianceMenu::find($this->compliance_menu_id);

    }


    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\ComplianceSubMenu::query()->where('compliance_menu_id', $this->compliance_menu_id))
            ->columns([
                TextColumn::make('sub_menu_name')->label('Compliance Type Name')
                    ->url(function (\App\Models\ComplianceSubMenu $record) {

                            return CompliancePrimarySubMenuList::getUrl([
                                'compliant_menu_id' => $record->compliance_menu_id,
                                'compliant_sub_menu_id' => $record->id,
                                'calendar_year_id' => $record->calendar_year_id,
                            ]);
                    })
                    ->extraAttributes(function (ComplianceSubMenu $record) {
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
                    ->icon('heroicon-o-trash')->button()
                    ->label('Delete')
                    ->requiresConfirmation()
                    ->action(function (array $data, $record, $form): void {
                        $compliancePrimarySubMenu = CompliancePrimarySubMenu::where('compliance_sub_menu_id', $record->id)->delete();
                        $complianceSubMenu = ComplianceSubMenu::find($record->id)->delete();

                        Notification::make()
                            ->title('Deleted Successfully')
                            ->success()
                            ->send();
//                        }


                    })

                    ->visible(function () {

                        if (auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Compliance Manager')) {

                            return true;
                        }
                        return false;
                    }),
                Action::make('upload_file')->color('success')
                    ->icon('heroicon-o-clipboard-document')->button()
                    ->label('Upload File')
//                    ->mountUsing(function (ComponentContainer $form) {
//                        $form->fill([
//                            'media' => $form->getRecord()->getMedia('compliant_attachments'),
//                        ]);
//                    })
                    ->form([
                        Card::make()
                            ->schema([
                                FileUpload::make('document')
                                    ->label('Upload File')
                                    ->model()
//                                    ->collection('compliant_attachments')
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

                        $complianceUploadDocument = UploadDocument::where('compliance_sub_menu_id', $record->id)->first();


                        foreach ($data['document'] as $document) {
                            $complianceUploadDocument->addMedia(storage_path('app/public/' . $document))->toMediaCollection('upload_documents');
                        }
                        $complianceSubMenu = \App\Models\ComplianceSubMenu::find($record->id);
                        $complianceSubMenu->upload_comment = $data['upload_comment'];
                        $complianceSubMenu->is_uploaded = 1;
                        $complianceSubMenu->upload_by = auth()->id();
                        $complianceSubMenu->upload_date = Carbon::now();
                        $complianceSubMenu->save();

                        $complianceUploadDocument = \App\Models\UploadDocument::whereComplianceSubMenuId($record->id)->first();
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
                    ->visible(function (ComplianceSubMenu $record) {
                        return false;
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
