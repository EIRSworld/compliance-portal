<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CalendarYearResource\Pages;
use App\Filament\Resources\CalendarYearResource\RelationManagers;
use App\Models\CalendarYear;
use App\Models\ComplianceMenu;
use App\Models\ComplianceSubMenu;
use App\Models\Country;
use App\Models\Document;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CalendarYearResource extends Resource
{
    protected static ?string $model = CalendarYear::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Masters';
    protected static ?string $navigationLabel = 'Years';
    protected static ?string $label = 'Years';

    public static function shouldRegisterNavigation(): bool
    {
        if (auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('compliance_manager')) {
            return true;
        }
        return false;
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('name')
                        ->label('Year')
                        ->unique(ignoreRecord: true),
                    Select::make('country_id')->label('Countries')->multiple()
                        ->options(Country::pluck('name', 'id')->toArray())
                        ->default(Country::pluck('id')->toArray())
                        ->searchable(),
//                Forms\Components\DatePicker::make('start_date')
//                    ->closeOnDateSelection()
//                    ->displayFormat('d-m-Y')
//                    ->label('Start date')
//                    ->unique(ignoreRecord: true),
//
//                Forms\Components\DatePicker::make('end_date')
//                    ->closeOnDateSelection()
//                    ->displayFormat('d-m-Y')
//                    ->label('End date')
//                    ->unique(ignoreRecord: true),
                ])->columns(1)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('name')->searchable(),
//                Tables\Columns\TextColumn::make('start_date')->searchable()->date('d-m-Y'),
//                Tables\Columns\TextColumn::make('end_date')->searchable()->date('d-m-Y'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->modalWidth('md')
                    ->after(function (CalendarYear $record) {
//                        $newCountryIds = request('country_id', []);// Assuming 'country_id' is the name of the input field in your form
                        $newCountryIds = $record->country_id;
//                        dd($newCountryIds);
                        $existingDocuments = Document::where('calendar_year_id', $record->id)->get();
                        $existingCountryIds = $existingDocuments->pluck('country_id')->toArray();
//                        dd($existingCountryIds);

                        // Find country IDs to add and to remove
                        $toAdd = array_diff($newCountryIds, $existingCountryIds);
                        $toRemove = array_diff($existingCountryIds, $newCountryIds);
//                        dd($newCountryIds,$existingCountryIds,$toAdd,$toRemove);

                        // Handle new countries
                        foreach ($toAdd as $countryId) {
                            $document = new Document();
                            $country = Country::find($countryId);
                            $document->country_id = $countryId;
                            $document->calendar_year_id = $record->id;
                            $document->name = $country->name;
                            $document->save();

                            $compliantMenu = new ComplianceMenu();
                            $compliantMenu->calendar_year_id = $record->id;
                            $compliantMenu->document_id = $document->id;
                            $compliantMenu->name = 'Compliance docs with due dates';
                            $compliantMenu->save();

                            $compliantSubMenu = new ComplianceSubMenu();
                            $compliantSubMenu->calendar_year_id = $record->id;
                            $compliantSubMenu->year = $record->name;
                            $compliantSubMenu->document_id = $document->id;
                            $compliantSubMenu->country_id = $countryId;
                            $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                            $compliantSubMenu->name = 'PI COVER/3RD PARTY COVER';
                            $compliantSubMenu->save();

                            $compliantSubMenu = new ComplianceSubMenu();
                            $compliantSubMenu->calendar_year_id = $record->id;
                            $compliantSubMenu->year = $record->name;
                            $compliantSubMenu->document_id = $document->id;
                            $compliantSubMenu->country_id = $countryId;
                            $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                            $compliantSubMenu->name = 'AUDITED FINANCIALS';
                            $compliantSubMenu->save();
                            // Similar logic to your create action for adding new documents and related entities
                        }

                        // Handle removal of countries and their documents
                        foreach ($toRemove as $countryId) {
                            $document = Document::where('calendar_year_id', $record->id)->where('country_id', $countryId)->first();
                            if ($document) {
//                                dd($document);

                                $complianceMenu = ComplianceMenu::where('document_id',$document->id)->delete();
                                $complianceSubMenu = ComplianceSubMenu::where('document_id',$document->id)->delete();
                                // Assuming you have cascading deletes set up in your database or you manually delete related records
                                $document->delete();
                            }
                        }

                        // Update any other necessary details for remaining documents
//                        foreach ($existingDocuments as $document) {
//                            if (in_array($document->country_id, $newCountryIds)) {
//                                // Update document details if needed
//                            }
//                        }
                    }),
//                Action::make('active_status')->button()->modalWidth('sm')->requiresConfirmation()
//                    ->label('Active')
//                    ->visible(function (CalendarYear $record ) {
//                        $calendarYearCount = CalendarYear::count();
//                        $calendarYear = CalendarYear::whereStatus(0)->count();
//                        if ($calendarYearCount === $calendarYear)
//                        {
//                            return true;
//                        }
//
//                        return false;
//                    })
//                    ->action(function (array $data, $record): void {
//
//                        $calendarYear = CalendarYear::find($record->id);
//                        $calendarYear->status = 1;
//                        $calendarYear->save();
//
//                        Notification::make()
//                            ->title('Year Active Successfully')
//                            ->success()
//                            ->send();
//
//                    }),
//                Action::make('inactive_status')->button()->modalWidth('sm')->requiresConfirmation()
//                    ->label('Inactive')
//                    ->visible(function (CalendarYear $record ) {
////                        $calendarYearCount = CalendarYear::count();
//                        $calendarYear = CalendarYear::find($record->id);
////dd($calendarYear);
//                        if ($calendarYear->status === 1)
//                        {
//                            return true;
//                        }
//
//                        return false;
//                    })
//                    ->action(function (array $data, $record): void {
//
//                        $calendarYear = CalendarYear::find($record->id);
//                        $calendarYear->status = 0;
//                        $calendarYear->save();
//
//                        Notification::make()
//                            ->title('Year Inactive Successfully')
//                            ->success()
//                            ->send();
//
//                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCalendarYears::route('/'),
//            'create' => Pages\CreateCalendarYear::route('/create'),
//            'edit' => Pages\EditCalendarYear::route('/{record}/edit'),
        ];
    }
}