<?php

namespace App\Filament\Pages;

use App\Exports\ManagementExport;
use App\Models\CalendarYear;
use App\Models\ComplianceMenu;
use App\Models\CompliancePrimarySubMenu;
use App\Models\ComplianceSubMenu;
use App\Models\Country;
use App\Models\Entity;
use App\Models\UploadDocument;
use App\Models\User;
use Carbon\Carbon;
use Filament\Actions\ActionGroup;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\ActionsPosition;
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
    protected static ?string $navigationLabel = 'Manage Event';

    protected static ?string $title = 'Manage Event';

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
//            ->query(function (Builder $query) {
//
//                $query = CompliancePrimarySubMenu::query();
//
//                return $query;
//            })
            ->query(function () {

                $user = auth()->user();
                $query = CompliancePrimarySubMenu::query();

                if ($user->hasRole('Compliance Manager')) {
                    $userId = User::find($user->id);
                    $complianceSubMenu = ComplianceSubMenu::where('sub_menu_name', $userId->compliance_type)->pluck('id');

                    return $query->whereIn('compliance_sub_menu_id', $complianceSubMenu);
                }
                if ($user->hasAnyRole(['Country Head', 'Cluster Head',])) {
                    $userId = User::find($user->id);
                    return $query->whereIn('country_id', $userId->country_id);
                }
                if ($user->hasRole('Compliance Officer')) {
                    $userId = User::find($user->id);

                    return $query->where('assign_id', $userId->id);
                }

                return $query;
            })
            ->columns([

                TextColumn::make('country.name')->label('Country'),
                TextColumn::make('entity.entity_name')->label('Entity Name'),
                TextColumn::make('complianceSubMenu.sub_menu_name')->label('Compliance Type'),
                TextColumn::make('event_name')->label('Event Name'),
                TextColumn::make('due_date')->label('Due Date')
                    ->formatStateUsing(function (CompliancePrimarySubMenu $record) {
                        $complianceExpiredDate = Carbon::parse($record->due_date);
                        $currentDate = Carbon::now();
                        if ($complianceExpiredDate > $currentDate) {

                            $daysDiff = $complianceExpiredDate->diffInDays($currentDate);
                        } else {
                            $daysDiff = '-' . $complianceExpiredDate->diffInDays($currentDate);
                        }

                        return $complianceExpiredDate->format('d-M-Y') . ' (' . $daysDiff . ' days)';
                    }),

                BadgeColumn::make('is_uploaded')
                    ->label('Status')
//                    ->trueIcon('heroicon-o-check-circle')
//                    ->falseIcon('heroicon-o-x-circle')
//                    ->boolean()
                    ->color(function (CompliancePrimarySubMenu $record) {
                        if ($record->is_uploaded == 1) {
                            return 'success';

                        } elseif ($record->is_uploaded == 0) {
                            return 'danger';
                        }
                        return '';
                    })
                    ->formatStateUsing(function (CompliancePrimarySubMenu $record) {
                        if ($record->is_uploaded == 1) {
                            return 'Uploaded';

                        } elseif ($record->is_uploaded == 0) {
                            return 'Not Upload';
                        }
                        return '';
                    }),
                BadgeColumn::make('approve_status')->label('Status')
                    ->color(function (CompliancePrimarySubMenu $record) {
                        if ($record->approve_status == 1) {
                            return 'success';

                        } elseif ($record->approve_status == 2) {
                            return 'danger';
                        }
                        return '';
                    })
                    ->formatStateUsing(function (CompliancePrimarySubMenu $record) {
                        if ($record->approve_status == 1) {
                            return 'Approved';

                        } elseif ($record->approve_status == 2) {
                            return 'Rejected';
                        }
                        return '';
                    }),

                ViewColumn::make('id')->label('Documents')->view('document.compliance-primary-sub-menu'),
                ViewColumn::make('ids')->label('Email Documents')->view('document.compliance-mail-document'),
//                TextColumn::make('upload_comment')->label('Comment')
//                    ->getStateUsing(function (CompliancePrimarySubMenu $record) {
//                        if ($record->reject_comment == null || $record->reject_comment == '') {
//                        return Str::limit($record->upload_comment, 20);
//                        }
//                        elseif ($record->reject_comment != null || $record->reject_comment != ''){
//                            return Str::limit($record->reject_comment, 20);
//                        }
//                        else{
//                            return '-';
//                        }
//
//                    })
//                    ->tooltip(function (CompliancePrimarySubMenu $record): ?string {
//                        if (!empty($record->reject_comment)) {
//                            // If there's a reject_comment, use it for the tooltip, and no need to limit since it's a tooltip
//                            return $record->reject_comment;
//                        } elseif (!empty($record->upload_comment)) {
//                            // Use the upload_comment for the tooltip if reject_comment is empty
//                            return $record->upload_comment;
//                        }
//                        // Return null or a default tooltip text if both comments are empty
//                        return null; // or use a string like 'No comments' if you prefer
//                    }),

                TextColumn::make('uploadBy.name')->label('Uploaded By')
                    ->visible(function () {
                        if (auth()->user()->hasRole('Compliance Officer')) {
                            return false;
                        }
                        return true;
                    }),
                TextColumn::make('approveBy.name')->label('Approved By')
                    ->visible(function () {
                        if (auth()->user()->hasRole('Compliance Manager')) {
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
                        $now = Carbon::now();
                        $currentYear = $now->year;
                        if ($now->format('Y-m-d') <= $currentYear . "-03-31") {
                            $previousYear = $currentYear - 1;
                            $financeYear = $previousYear . '-' . $currentYear;
                        } else {
                            $nextYear = $currentYear + 1;
                            $financeYear = $currentYear . '-' . $nextYear;
                        }
                        return CalendarYear::where('name', $financeYear)->value('id');
                    })
                    ->placeholder('Select the Year')
                    ->label('Year'),
                SelectFilter::make('country_id')->searchable()
                    ->options(function () {
                        return Country::pluck('name', 'id')->toArray();
                    })
                    ->placeholder('Select the Country')
                    ->label('Country'),
                SelectFilter::make('entity_id')->searchable()
                    ->options(function () {
                        return Entity::pluck('entity_name', 'id')->toArray();
                    })
                    ->placeholder('Select the Entity')
                    ->label('Entity Name'),
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
                    ->mountUsing(function (ComponentContainer $form, CompliancePrimarySubMenu $record) {
//                        dd($record);
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
//
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
//                        dd($mediaFile);

                        if ($mediaFile) {
                            $compliancePrimarySubMenu = \App\Models\CompliancePrimarySubMenu::find($record->id);

                            if ($compliancePrimarySubMenu->is_uploaded == 0) {
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

                        if ($record->approve_status != 1 || auth()->user()->hasRole('Super Admin')) {
                            return true;
                        }
                        return false;
                    })
                    ->modalWidth('lg'),

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
                    ->mountUsing(function (ComponentContainer $form, CompliancePrimarySubMenu $record) {
//                        dd($record);
                        $form->fill([
                            'approve_status' => $record->approve_status,
                            'reject_comment' => $record->reject_comment,
                        ]);
                    })
                    ->form([
                        Card::make()
                            ->schema([
                                Radio::make('approve_status')->reactive()->boolean()
                                    ->options([
                                        '1' => 'Approve',
                                        '2' => 'Reject',
                                    ])->required(),


                                Textarea::make('reject_comment')->rows(2)->reactive()
                                    ->label('Reason'),
//                                Select::make('status')->required()
//                                    ->options([
//                                        'Green' => 'Green',
//                                        'Blue' => 'Blue',
//                                    ])
//                                    ->visible(function (callable $get) {
//                                        if ($get('approve_status') === '1') {
//                                            return true;
//                                        }
//                                        return false;
//                                    })
//                                    ->searchable()->reactive(),
                            ])
                    ])
                    ->action(function (array $data, $record): void {

                        $compliancePrimarySubMenu = \App\Models\CompliancePrimarySubMenu::find($record->id);
                        $compliancePrimarySubMenu->approve_status = $data['approve_status'];
                        if ($data['approve_status'] == 1) {
                            $compliancePrimarySubMenu->status = "Blue";
                        } elseif ($data['approve_status'] == 2) {
                            $compliancePrimarySubMenu->is_uploaded = 0;
                            $compliancePrimarySubMenu->upload_by = null;
                            $compliancePrimarySubMenu->upload_date = null;
                            $compliancePrimarySubMenu->upload_comment = null;

                            if (Carbon::parse($record->due_date)->gt(Carbon::now())) {
                                $compliancePrimarySubMenu->status = "Amber";
                            } else {
                                $compliancePrimarySubMenu->status = "Red";
                            }

                            $medias = Media::whereModelType('App\Models\CompliancePrimarySubMenu')->whereModelId($record->id)->whereCollectionName('compliance_primary_attachments')->pluck('id')->toArray();

                            foreach ($medias as $media){
                                $deleteMedia = Media::find($media)->delete();
                            }

                        }
                        $compliancePrimarySubMenu->approve_by = auth()->id();
                        $compliancePrimarySubMenu->approve_date = Carbon::now();
                        $compliancePrimarySubMenu->reject_comment = $data['reject_comment'];

                        $compliancePrimarySubMenu->save();

//                        $user = User::find($record->upload_by);
//                        $da = [
//                            'subject' => 'Reminder Email',
//                            'name' => $user->name,
//                            'email' => $user->email,
//                            'complianceUploadDocument' => $compliancePrimarySubMenu,
//                        ];
//                        Mail::send('mail.management-status', $da, function ($message) use ($da, $user) {
//                            $message->to($user->email, config('app.name'));
//                        });
                        Notification::make()
                            ->title('Mail Sent Successfully')
                            ->success()
                            ->send();


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


//                Action::make('status_flow')->color('danger')->button()
//                    ->action(function (CompliancePrimarySubMenu $record) {
//
//                    })
//                    ->modalContent(function (CompliancePrimarySubMenu $record) {
//                        return view('status-flow', [
//                            'compliancePrimarySubmenu' => $record,
//                            'hideCloseButton' => true
//                        ]);
//                    })
//                    ->icon('heroicon-o-eye')
//                    ->slideOver()
////                    ->modalActions([
////                        \Filament\Pages\Actions\Modal\Actions\Action::make('close')->label('Close')->cancel()
////                    ])
//                ->label('Status Flow')
//                    ->label(function (Order $order) {
//                        return (__('admin.show_order')) . $order->order_no;
//                    }),

//                Action::make('delete_uploaded_file')->button()->color('danger')
//                    ->label('Delete Uploaded File')
//                    ->icon('heroicon-o-trash')->button()
//                    ->requiresConfirmation()
//                    ->action(function ($record): void{
//                        $document = \Spatie\MediaLibrary\MediaCollections\Models\Media::whereModelId($record->id);
//                        if ($document) {
//                            $document->delete();
//                            Notification::make()
//                                ->title('Deleted Successfully')
//                                ->success()
//                                ->send();
//                        }
//                    })
//                    ->visible(function () {
//
//                        if (auth()->user()->can('delete Compliance Management')) {
//                            return true;
//                        }
//                        return false;
//                    }),


            ], position: ActionsPosition::AfterColumns);
    }
}
