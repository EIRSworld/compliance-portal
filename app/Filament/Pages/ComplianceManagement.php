<?php

namespace App\Filament\Pages;

use App\Exports\ManagementExport;
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
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ComplianceManagement extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.compliance-management';
    protected static ?string $navigationLabel = 'Compliance Management';

    protected static ?string $title = 'Compliance Management';

    protected static ?int $navigationSort = 3;

    public static function canAccess(): bool
    {
        return auth()->user()->can('view Compliance Management');
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('export')
                ->label('Download')->color('success')
                ->icon('heroicon-o-arrow-down-circle')
                ->action(function () {
                    return Excel::download(new ManagementExport($this->getFilteredTableQuery()->get()), 'Management.xlsx',);
                }),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {

                $user = auth()->user();
//                $query = UploadDocument::where('is_expired', '=', 1);
                $query = UploadDocument::query();

                if ($user->hasAnyRole([
                        'Country Head',
                        'Cluster Head',
                        'Compliance Finance Manager',
                        'Compliance Principle Manager',
                        'Compliance Finance Officer',
                        'Compliance Principle Officer'
                    ]) && !is_null($user->country_id)) {
                    $countryId = $user->country_id;
//                    dd($query->whereIn('country_id', $user->country_id)->get());

                    $query->whereIn('country_id', $user->country_id);
                }


                return $query->where('is_expired', '=', 1);
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
                TextColumn::make('expired_date')->label('Due Date')
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
                BadgeColumn::make('approve_status')->label('Approve Status')
                    ->color(function (UploadDocument $record) {
                        if ($record->approve_status == 1) {
                            return 'success';

                        } elseif ($record->approve_status == 2) {
                            return 'danger';
                        }
                        return '';
                    })
                    ->formatStateUsing(function (UploadDocument $record) {
                        if ($record->approve_status == 1) {
                            return 'Approved';

                        } elseif ($record->approve_status == 2) {
                            return 'Rejected';
                        }
                        return '';
                    }),
//                IconColumn::make('approve_status')
//                    ->label('Approve Status')
//                    ->trueIcon('heroicon-o-check-circle')
//                    ->falseIcon('heroicon-o-x-circle'),

                ViewColumn::make('id')->label('Documents')->view('document.compliance-management'),
                TextColumn::make('upload_comment')->label('Comment')
                    ->getStateUsing(function (UploadDocument $record) {
                        if ($record->reject_comment == null || $record->reject_comment == '') {
                        return Str::limit($record->upload_comment, 20);
                        }
                        elseif ($record->reject_comment != null || $record->reject_comment != ''){
                            return Str::limit($record->reject_comment, 20);
                        }
                        else{
                            return '-';
                        }

                    })
                    ->tooltip(function (UploadDocument $record): ?string {
                        if (!empty($record->reject_comment)) {
                            // If there's a reject_comment, use it for the tooltip, and no need to limit since it's a tooltip
                            return $record->reject_comment;
                        } elseif (!empty($record->upload_comment)) {
                            // Use the upload_comment for the tooltip if reject_comment is empty
                            return $record->upload_comment;
                        }
                        // Return null or a default tooltip text if both comments are empty
                        return null; // or use a string like 'No comments' if you prefer
                    }),
//                    ->tooltip(fn(UploadDocument $record): string|null => $record->upload_comment),
//                    ->visible(function (UploadDocument $record) {
//                        if ($record->reject_comment == null || $record->reject_comment == '') {
//                            return true;
//                        }
//                        return false;
//                    }),
//                TextColumn::make('reject_comment')->label('Reject Comment')
//                    ->getStateUsing(function (UploadDocument $record) {
//                        return Str::limit($record->reject_comment, 20);
//                    })
//                    ->tooltip(fn(UploadDocument $record): string|null => $record->reject_comment),
//                    ->visible(function (UploadDocument $record) {
//
//                        if ($record->reject_comment != null || $record->reject_comment != ''){
//                            return true;
//                        }
//                        return false;
//                    }),

                TextColumn::make('uploadBy.name')->label('Uploaded By')
                    ->visible(function () {
                        if (auth()->user()->hasRole('Compliance Finance Officer') || auth()->user()->hasRole('Compliance Principle Officer')) {
                            return false;
                        }
                        return true;
                    }),
                TextColumn::make('approveBy.name')->label('Approved By')
                    ->visible(function () {
                        if (auth()->user()->hasRole('Compliance Finance Manager') || auth()->user()->hasRole('Compliance Principle Manager')) {
                            return false;
                        }
                        return true;
                    }),
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
//                        $role = auth()->user()->roles()->pluck('name')[0];
//                        if ($role === 'country_head'){
//                            $userCountry = User::whereId(auth()->id())->value('country_id');
////                            dd($userCountry);
//                            return Country::whereIn('id',$userCountry)->pluck('name', 'id')->toArray();
//                        }
                        return Country::pluck('name', 'id')->toArray();
                    })
                    ->placeholder('Select the Country')
                    ->label('Country'),
                SelectFilter::make('is_uploaded')->searchable()
                    ->options([
                        '1' => 'Upload',
                        '0' => 'Not Upload',
                    ])
                    ->placeholder('Select the Status')
                    ->label('Upload Status'),
                SelectFilter::make('approve_status')->searchable()
                    ->options([
                        '1' => 'Approve',
                        '2' => 'Reject',
                    ])
                    ->placeholder('Select the Status')
                    ->label('Approve Status'),

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
                            $complianceMenu->upload_by = auth()->id();
                            $complianceMenu->upload_date = Carbon::now();
                            $complianceMenu->save();
                        } elseif (($record->compliance_primary_sub_menu_id === null || $record->compliance_primary_sub_menu_id === '')) {

                            $complianceSubMenu = \App\Models\ComplianceSubMenu::find($record->compliance_sub_menu_id);
                            $complianceSubMenu->upload_comment = $data['upload_comment'];
                            $complianceSubMenu->is_uploaded = 1;
                            $complianceSubMenu->upload_by = auth()->id();
                            $complianceSubMenu->upload_date = Carbon::now();
                            $complianceSubMenu->save();
                        } else {

                            $compliancePrimarySubMenu = \App\Models\CompliancePrimarySubMenu::find($record->compliance_primary_sub_menu_id);
                            $compliancePrimarySubMenu->upload_comment = $data['upload_comment'];
                            $compliancePrimarySubMenu->is_uploaded = 1;
                            $compliancePrimarySubMenu->upload_by = auth()->id();
                            $compliancePrimarySubMenu->upload_date = Carbon::now();
                            $compliancePrimarySubMenu->save();
                        }

                        $complianceUploadDocument = \App\Models\UploadDocument::find($record->id);
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
                    ->visible(function () {

                        if (auth()->user()->can('upload Compliance Management')) {
                            return true;
                        }
                        return false;
                    })
                    ->modalWidth('md'),
                Action::make('approve_status')->button()->modalWidth('sm')
                    ->label('Change Status')
                    ->disabled(function ($record) {
                        if ($record->is_uploaded === 0) {
                            return true;
                        }
                        return false;
                    })
                    ->color(function ($record) {
                        if ($record->is_uploaded === 0) {
                            return 'primary';
                        }
                        return 'warning';
                    })
                    ->form([
                        Card::make()
                            ->schema([
                                Radio::make('approve_status')->reactive()->boolean()
                                    ->options([
                                        '1' => 'Approve',
                                        '2' => 'Reject',
                                    ]),


                                Textarea::make('reject_comment')->rows(2)->reactive()
                                    ->label('Reason')
//                                    ->visible(function (callable $get) {
//                                        $approveStatus = $get('approve_status');
//                                        if ($approveStatus === '2') {
//                                            return true;
//                                        }
//                                        return false;
//                                    })
                            ])
                    ])
                    ->action(function (array $data, $record): void {

                        $complianceUploadDocument = \App\Models\UploadDocument::find($record->id);
                        $complianceUploadDocument->approve_status = $data['approve_status'];
                        $complianceUploadDocument->approve_by = auth()->id();
                        $complianceUploadDocument->approve_date = Carbon::now();
                        $complianceUploadDocument->reject_comment = $data['reject_comment'];
                        $complianceUploadDocument->save();

                        $user = User::find($record->upload_by);
                        $da = [
                            'subject' => 'Reminder Email',
                            'name' => $user->name,
                            'email' => $user->email,
                            'complianceUploadDocument' => $complianceUploadDocument,
                        ];
                        Mail::send('mail.management-status', $da, function ($message) use ($da, $user) {
                            $message->to($user->email, config('app.name'));
                        });
                        Notification::make()
                            ->title('Mail Sent Successfully')
                            ->success()
                            ->send();


                        if (($record->compliance_primary_sub_menu_id === null || $record->compliance_primary_sub_menu_id === '') && ($record->compliance_sub_menu_id === null || $record->compliance_sub_menu_id === '')) {
                            $complianceMenu = \App\Models\ComplianceMenu::find($record->compliance_menu_id);

                            $complianceMenu->approve_status = $data['approve_status'];
                            $complianceMenu->approve_by = auth()->id();
                            $complianceMenu->approve_date = Carbon::now();
                            $complianceMenu->reject_comment = $data['reject_comment'];

                            $complianceMenu->save();
                        } elseif (($record->compliance_primary_sub_menu_id === null || $record->compliance_primary_sub_menu_id === '')) {
                            $complianceSubMenu = \App\Models\ComplianceSubMenu::find($record->compliance_sub_menu_id);

                            $complianceSubMenu->approve_status = $data['approve_status'];
                            $complianceSubMenu->approve_by = auth()->id();
                            $complianceSubMenu->approve_date = Carbon::now();
                            $complianceSubMenu->reject_comment = $data['reject_comment'];

                            $complianceSubMenu->save();
                        } else {
                            $compliancePrimarySubMenu = \App\Models\CompliancePrimarySubMenu::find($record->compliance_primary_sub_menu_id);
                            $compliancePrimarySubMenu->approve_status = $data['approve_status'];
                            $compliancePrimarySubMenu->approve_by = auth()->id();
                            $compliancePrimarySubMenu->approve_date = Carbon::now();
                            $compliancePrimarySubMenu->reject_comment = $data['reject_comment'];

                            $compliancePrimarySubMenu->save();
                        }

                        Notification::make()
                            ->title('Successfully Updated')
                            ->success()
                            ->send();

                    })
                    ->visible(function () {

                        if (auth()->user()->can('change Status Compliance Management')) {
                            return true;
                        }
                        return false;
                    }),
//                 Action::make('approved_status')->button()->modalWidth('sm')
//                     ->label('Change Status')->color('danger')
//                     ->visible(function ($record) {
//                         if (auth()->user()->hasRole('Super Admin')) {
//                             if ($record->is_uploaded === 0){
//                             return true;
//                             }
//                         }
//                         return false;
//                     })

            ]);
    }
}
