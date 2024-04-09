<?php

namespace App\Filament\Pages;

use App\Exports\ComplianceEmailExport;
use App\Models\ComplianceEmail;
use App\Models\ComplianceEvent;
use App\Models\Country;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;

class ComplianceEmailList extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static string $view = 'filament.pages.compliance-email-list';

    protected static ?string $navigationLabel = 'Compliance Email';

    protected static ?string $title = 'Compliance Email';

    protected static ?int $navigationSort = 5;

//    public static function canAccess(): bool
//    {
//        return auth()->user()->can('view Compliance Email');
//    }

    protected function getHeaderActions(): array
    {
        return [

            Action::make('email')->label('Attach Email')
                ->form([
                    Section::make()->schema([
                        Select::make('country_id')->label('Country')->searchable()
                            ->options(Country::pluck('name','id'))->columnSpan(1),

                        Select::make('user_id')->label('Assign a Owner')
                            ->options(User::pluck('name','id'))
                            ->searchable()->columnSpan(1),

                        TextInput::make('subject')->label('Subject')->columnSpan(2),

                        FileUpload::make('mail_media')
                            ->preserveFilenames()
                            ->columnSpan(2)
                            ->label('File Upload')
                    ])->columns(2)
                ])
                ->action(function (array $data): void{
                    $complianceEmail = new ComplianceEmail();
                    $complianceEmail->country_id = $data['country_id'];
                    $complianceEmail->subject = $data['subject'];
                    $complianceEmail->user_id = $data['user_id'];
                    $complianceEmail->upload_by = auth()->user()->id;
                    $complianceEmail->save();

                    $complianceEmail->addMedia(storage_path('app/public/' . $data['mail_media']))->toMediaCollection('mail_document');

                    Notification::make()
                        ->title('Mail Created Successfully')
                        ->success()
                        ->send();
                }),

            \Filament\Actions\Action::make('export')
                ->label('Download')->color('success')
                ->icon('heroicon-o-arrow-down-circle')
                ->action(function () {
                    $fileName = 'Compliance Email.xlsx';
                    return Excel::download(new ComplianceEmailExport($this->getFilteredTableQuery()->get()), $fileName);
                }),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(ComplianceEmail::query())
            ->columns([
                TextColumn::make('country.name')->label('Country')->searchable(),
                TextColumn::make('user.name')->label('User')->searchable(),
                TextColumn::make('subject')->label('Subject')->searchable(),
                TextColumn::make('uploadBy.name')->label('Uploaded By')->searchable(),
            ])->emptyStateHeading('No Data Found')
            ->filters([
                SelectFilter::make('country_id')->searchable()
                    ->options(function () {
                        return Country::pluck('name', 'id')->toArray();
                    })
                    ->placeholder('Select the Country')
                    ->label('Country'),
                SelectFilter::make('user_id')->searchable()
                    ->options(function () {
                        return User::pluck('name', 'id')->toArray();
                    })
                    ->placeholder('Select the User')
                    ->label('User'),
            ])
            ->filtersLayout(FiltersLayout::AboveContent)
            ->actions([
                \Filament\Tables\Actions\Action::make('view')->label('View')->button()
                    ->action(function (ComplianceEmail $record) {
                        $complianceId = ComplianceEmail::whereId($record->id)->first();
                        return $complianceId->getFirstMedia('mail_document');
                    })->color('success'),
                \Filament\Tables\Actions\Action::make('edit')
                    ->mountUsing(function (ComponentContainer $form, $record) {
                        $form->fill([
                            'country_id' => $record->country_id,
                            'user_id' => $record->user_id,
                            'subject' => $record->subject,
                        ]);
                    })
                    ->form([
                        Section::make()->schema([
                            Select::make('country_id')->label('Country')
                                ->options(Country::pluck('name','id'))->searchable(),

                            Select::make('user_id')->label('Assign a Owner')
                                ->options(User::pluck('name','id'))
                                ->searchable()->columnSpan(1),

                            TextInput::make('subject')->label('Subject')->columnSpan(2),

                            FileUpload::make('mail_media')
                                ->preserveFilenames()
                                ->columnSpan(2)
                                ->label('File Upload')

                        ])->columns(2)
                    ])
                    ->action(function (array $data, $record){
                        $complianceEmail = ComplianceEmail::find($record->id);
                        $complianceEmail->country_id = $data['country_id'];
                        $complianceEmail->subject = $data['subject'];
                        $complianceEmail->user_id = $data['user_id'];
                        $complianceEmail->upload_by = auth()->user()->id;
                        $complianceEmail->update();

                        $document = \Spatie\MediaLibrary\MediaCollections\Models\Media::whereModelId($record->id);
                        $document->delete();
                        if ($data['mail_media']) {
                            $complianceEmail->addMedia(storage_path('app/public/' . $data['mail_media']))->toMediaCollection('mail_document');
                        }

                        Notification::make()
                            ->title('Mail updated Successfully')
                            ->success()
                            ->send();
                    })
//                    ->visible(function () {
//                        if (auth()->user()->can('edit Compliance Email')) {
//                            return true;
//                        }
//                        return false;
//                    })
                    ->label('Edit')->button(),
                \Filament\Tables\Actions\DeleteAction::make('delete')->label('Delete')->button()
//                    ->visible(function () {
//                        if (auth()->user()->can('delete Compliance Email')) {
//                            return true;
//                        }
//                        return false;
//                    })
                ,
            ]);
    }
}
