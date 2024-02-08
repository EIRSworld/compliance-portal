<?php

namespace App\Filament\Widgets;

use App\Models\CalendarYear;
use App\Models\ComplianceSubMenu;
use App\Models\Country;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Query\Builder;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ComplianceTotal extends BaseWidget
{

    protected int|string|array $columnSpan = 2;
    protected static ?string $heading = 'Compliance Report';

//    public function mount()
//    {
//        $complianceSubMenu =ComplianceSubMenu::
//        $stocksMedia = Media::whereModelType('App\Models\StockEntry')->whereCollectionName('stock_documents')
//            ->groupBy('model_id')->get()->pluck('model_id')->toArray();
//    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function (Builder $query) {

                $baseQuery = ComplianceSubMenu::whereRelation('complianceMenu', 'name', 'Compliance docs with due dates')->orderBy('expired_date');
                if (auth()->user()->hasRole('country_head')) {

                    return $baseQuery->whereIn('country_id', auth()->user()->country_id);
                }

                return $baseQuery;
            })
            ->columns([

                Tables\Columns\TextColumn::make('document.name')->label('Country Name'),
//                Tables\Columns\TextColumn::make('complianceMenu.name')->label('Folder Name'),
                Tables\Columns\TextColumn::make('name')->label('Document Name'),
//                Tables\Columns\TextColumn::make('expired_date')->label('Expired Date')->date('d-m-Y'),
                Tables\Columns\TextColumn::make('expired_date')->label('Expired Date')
                    ->formatStateUsing(function (ComplianceSubMenu $record) {
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
                    })
                    ->extraAttributes(function (ComplianceSubMenu $record) {
                        $complianceExpiredDate = Carbon::parse($record->expired_date);
                        $currentDate = Carbon::now();
//                        if ($complianceExpiredDate > $currentDate) {
                        if ($record->is_uploaded == 1) {
                            return [
                                'class' => 'custom-bg-green',
                            ];
                        } else {
                            return [
                                'class' => 'custom-bg-red',
                            ];
                        }
                    }),
                Tables\Columns\IconColumn::make('is_uploaded')
                    ->label('Upload Status')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->boolean(),
                Tables\Columns\IconColumn::make('approve_status')
                    ->label('Approve Status')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('calendar_year_id')->searchable()
                    ->options(function () {
                        return CalendarYear::pluck('name', 'id')->toArray();
                    })

                    ->default(function(){
                        $currentYear = Carbon::now()->year;
                        return CalendarYear::where('name', $currentYear)->value('id');
                    })
                    ->placeholder('Select the Country')
                    ->label('Year'),
                SelectFilter::make('country_id')->searchable()
                    ->options(function () {
                        $role = auth()->user()->roles()->pluck('name')[0];
                        if ($role === 'country_head'){
                            $userCountry = User::whereId(auth()->id())->value('country_id');
//                            dd($userCountry);
                            return Country::whereIn('id',$userCountry)->pluck('name', 'id')->toArray();
                        }
                            return Country::pluck('name', 'id')->toArray();
                    })
                    ->placeholder('Select the Country')
                    ->label('Country'),

            ], FiltersLayout::AboveContent)
            ->actions([
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
                    ->visible(function () {

                        if (auth()->user()->hasRole('country_head') || auth()->user()->hasRole('super_admin')) {
                            return true;
                        }

                        return false;
                    })
                    ->modalWidth('md'),
                Action::make('approve_status')->button()->modalWidth('sm')->requiresConfirmation()
                    ->label('Approve')
                    ->visible(function () {

                        if (auth()->user()->hasRole('compliance_manager') || auth()->user()->hasRole('super_admin')) {
                            return true;
                        }

                        return false;
                    })
                    ->action(function (array $data, $record): void {

                        $complianceSubMenu = \App\Models\ComplianceSubMenu::find($record->id);
                        $complianceSubMenu->approve_status = 1;
                        $complianceSubMenu->save();

                        Notification::make()
                            ->title('Approved Successfully')
                            ->success()
                            ->send();

                    }),

            ]);
    }
}
