<?php

namespace App\Filament\Pages;

use App\Models\CalendarYear;
use App\Models\ComplianceMenu;
use App\Models\ComplianceSubMenu;
use App\Models\Country;
use App\Models\UploadDocument;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ComplianceManagement extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.compliance-management';
    protected static ?string $navigationLabel = 'Compliance Management';

    protected static ?string $title = 'Compliance Management';

    protected static ?int $navigationSort = 3;


    public function table(Table $table): Table
    {
        return $table
            ->query(function (Builder $query) {

                $baseQuery = UploadDocument::where('is_expired', '=', 1);
//                    ->whereRelation('complianceSubMenu', 'is_expired', '=',1)
//                    ->whereRelation('compliancePrimarySubMenu', 'is_expired','=',1)
//                    ->orderBy('expired_date');
////                if (auth()->user()->hasRole('country_head')) {
////
////                    return $baseQuery->whereIn('country_id', auth()->user()->country_id);
////                }
//
                return $baseQuery;
            })
            ->columns([

                TextColumn::make('country.name')->label('Country'),
//                TextColumn::make('complianceSubMenu.sub_menu_name')->label('Name'),
//                Tables\Columns\TextColumn::make('complianceMenu.name')->label('Folder Name'),
                TextColumn::make('name')->label('Document Name'),
//                    ->url(fn(\App\Models\ComplianceSubMenu $record): string => CompliantView::getUrl([
//                        'compliant_sub_menu_id' => $record->id,
//                        'calendar_year_id' => $record->calendar_year_id,
//
//                    ]))->openUrlInNewTab(),
//                TextColumn::make('expired_date')->label('Expired Date')->date('d-m-Y'),
                TextColumn::make('expired_date')->label('Expired Date')
                    ->formatStateUsing(function (UploadDocument $record) {
                        $complianceExpiredDate = Carbon::parse($record->expired_date);
                        $currentDate = Carbon::now();
                        if ($complianceExpiredDate > $currentDate) {

                            $daysDiff = $complianceExpiredDate->diffInDays($currentDate);
                        } else {
                            $daysDiff = '-' . $complianceExpiredDate->diffInDays($currentDate);
                        }
//dd($daysDiff);
//                    return $daysDiff . ' days';
//
//                }),
                        return $complianceExpiredDate->format('d-M-Y') . ' (' . $daysDiff . ' days)';
                    }),
//                    ->extraAttributes(function (UploadDocument $record) {
//                        $complianceExpiredDate = Carbon::parse($record->expired_date);
//                        $currentDate = Carbon::now();
////                        if ($complianceExpiredDate > $currentDate) {
////                        dd($record->approve_status == 1 && $record->is_uploaded == 1);
//                        if ($record->approve_status == 1 && $record->is_uploaded == 1) {
//                            return [
//                                'class' => 'custom-bg-green',
//                            ];
//                        } elseif ($record->is_uploaded == 1) {
//                            return [
//                                'class' => 'custom-bg-yellow',
//                            ];
//                        }
//                        else {
//                            return [
//                                'class' => 'custom-bg-red',
//                            ];
//                        }
//                    }),
                IconColumn::make('is_uploaded')
                    ->label('Upload Status')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->boolean(),
                IconColumn::make('approve_status')
                    ->label('Approve Status')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->boolean(),

                ViewColumn::make('id')->label('Documents')->view('document.compliance-management')
            ])
            ->filters([
                SelectFilter::make('calendar_year_id')->searchable()
                    ->options(function () {
                        return CalendarYear::pluck('name', 'id')->toArray();
                    })
                    ->default(function () {
                        $currentYear = Carbon::now()->year;
                        return CalendarYear::where('name', $currentYear)->value('id');
                    })
                    ->placeholder('Select the Year')
                    ->label('Year'),
                SelectFilter::make('country_id')->searchable()
                    ->options(function () {
                        $role = auth()->user()->roles()->pluck('name')[0];
//                        if ($role === 'country_head'){
//                            $userCountry = User::whereId(auth()->id())->value('country_id');
////                            dd($userCountry);
//                            return Country::whereIn('id',$userCountry)->pluck('name', 'id')->toArray();
//                        }
                        return Country::pluck('name', 'id')->toArray();
                    })
                    ->placeholder('Select the Country')
                    ->label('Country'),

            ], FiltersLayout::AboveContent)
            ->actions([
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
                                    ->appendFiles()
                                         ->required()
                                    ->downloadable()
                                    ->openable()
                                    ->multiple(),


                                Textarea::make('upload_comment')->rows(2)
                                    ->label('Comment'),
                            ])
                    ])
                    ->action(function (array $data, $record, $form): void {

//                        dd($record);
                        $complianceUploadDocument = UploadDocument::find($record->id);

                        foreach ($data['document'] as $document) {
                            $complianceUploadDocument->addMedia(storage_path('app/public/' . $document))->toMediaCollection('upload_documents');
                        }
                        if (($record->compliance_primary_sub_menu_id === null || $record->compliance_primary_sub_menu_id === '') && ($record->compliance_sub_menu_id === null || $record->compliance_sub_menu_id === '')) {

                            $complianceMenu = \App\Models\ComplianceMenu::find($record->compliance_menu_id);
                            $complianceMenu->upload_comment = $data['upload_comment'];
                            $complianceMenu->is_uploaded = 1;
                            $complianceMenu->save();
                        }
                        elseif (($record->compliance_primary_sub_menu_id === null || $record->compliance_primary_sub_menu_id === '')) {

                            $complianceSubMenu = \App\Models\ComplianceSubMenu::find($record->compliance_sub_menu_id);
                            $complianceSubMenu->upload_comment = $data['upload_comment'];
                            $complianceSubMenu->is_uploaded = 1;
                            $complianceSubMenu->save();
                        }
                        else {

                            $compliancePrimarySubMenu = \App\Models\CompliancePrimarySubMenu::find($record->compliance_primary_sub_menu_id);
                            $compliancePrimarySubMenu->upload_comment = $data['upload_comment'];
                            $compliancePrimarySubMenu->is_uploaded = 1;
                            $compliancePrimarySubMenu->save();
                        }

                        $complianceUploadDocument = \App\Models\UploadDocument::find($record->id);
                        $complianceUploadDocument->upload_comment = $data['upload_comment'];
                        $complianceUploadDocument->is_uploaded = 1;
                        $complianceUploadDocument->save();
                        Notification::make()
                            ->title('File Update Successfully')
                            ->success()
                            ->send();


                    })
                    ->visible(function () {

                        if (auth()->user()->hasRole('Super Admin')) {
                            return true;
                        }

                        return false;
                    })
                    ->modalWidth('md'),
                Action::make('approve_status')->button()->modalWidth('sm')
                    ->label('Change Status')
                    ->form([
                        Card::make()
                            ->schema([
                                Radio::make('approve_status')->reactive()->boolean()
                                    ->options([
                                        '1' => 'Approve',
                                        '0' => 'Reject',
                                    ]),


                                Textarea::make('reject_comment')->rows(2)->reactive()
                                    ->label('Comment')
                                    ->visible(function (callable $get) {
                                        $approveStatus = $get('approve_status');
                                        if ($approveStatus === '0') {
                                            return true;
                                        }
                                        return false;
                                    })
                            ])
                    ])
                    ->action(function (array $data, $record): void {

                        $complianceUploadDocument = \App\Models\UploadDocument::find($record->id);
                        $complianceUploadDocument->approve_status = $data['approve_status'];
                        if ($data['approve_status'] === '0' && !empty($data['reject_comment'])) {
                            $complianceUploadDocument->reject_comment = $data['reject_comment'];
                        } elseif ($data['approve_status'] === '1') {
                            $complianceUploadDocument->reject_comment = null;
                        }
                        $complianceUploadDocument->save();


                        if (($record->compliance_primary_sub_menu_id === null || $record->compliance_primary_sub_menu_id === '') && ($record->compliance_sub_menu_id === null || $record->compliance_sub_menu_id === '')) {
                            $complianceMenu = \App\Models\ComplianceMenu::find($record->compliance_menu_id);
                            $complianceMenu->approve_status = $data['approve_status'];
                            if ($data['approve_status'] === '0' && !empty($data['reject_comment'])) {
                                $complianceMenu->reject_comment = $data['reject_comment'];
                            } elseif ($data['approve_status'] === '1') {
                                $complianceMenu->reject_comment = null;
                            }
                            $complianceMenu->save();
                        }
                        elseif (($record->compliance_primary_sub_menu_id === null || $record->compliance_primary_sub_menu_id === '')) {
                            $complianceSubMenu = \App\Models\ComplianceSubMenu::find($record->compliance_sub_menu_id);
                            $complianceSubMenu->approve_status = $data['approve_status'];
                            if ($data['approve_status'] === '0' && !empty($data['reject_comment'])) {
                                $complianceSubMenu->reject_comment = $data['reject_comment'];
                            } elseif ($data['approve_status'] === '1') {
                                $complianceSubMenu->reject_comment = null;
                            }
                            $complianceSubMenu->save();
                        }
                        else {
                            $compliancePrimarySubMenu = \App\Models\CompliancePrimarySubMenu::find($record->compliance_primary_sub_menu_id);
                            $compliancePrimarySubMenu->approve_status = $data['approve_status'];
                            if ($data['approve_status'] === '0' && !empty($data['reject_comment'])) {
                                $compliancePrimarySubMenu->reject_comment = $data['reject_comment'];
                            } elseif ($data['approve_status'] === '1') {
                                $compliancePrimarySubMenu->reject_comment = null;
                            }
                            $compliancePrimarySubMenu->save();
                        }

                        Notification::make()
                            ->title('Approved Successfully')
                            ->success()
                            ->send();

                    })
                    ->visible(function ($record) {
                        if (auth()->user()->hasRole('Super Admin')) {
                            if ($record->is_uploaded === 1){

                                return true;
                            }
                        }
                        return false;
                    }),
                 Action::make('approved_status')->button()->modalWidth('sm')
                     ->label('Change Status')->color('danger')
                     ->visible(function ($record) {
                         if (auth()->user()->hasRole('Super Admin')) {
                             if ($record->is_uploaded === 0){
                             return true;
                             }
                         }
                         return false;
                     })

            ]);
    }
}
