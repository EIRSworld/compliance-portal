<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CalendarYearResource\Pages;
use App\Filament\Resources\CalendarYearResource\RelationManagers;
use App\Models\CalendarYear;
use App\Models\ComplianceMenu;
use App\Models\CompliancePrimarySubMenu;
use App\Models\ComplianceSubMenu;
use App\Models\Country;
use App\Models\Document;
use App\Models\Entity;
use App\Models\UploadDocument;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CalendarYearResource extends Resource
{
    protected static ?string $model = CalendarYear::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Masters';
    protected static ?string $navigationLabel = 'Financial Years';
    protected static ?int $navigationSort = 1;
    protected static ?string $label = 'Financial Years';

    public static function canAccess(): bool
    {
        return auth()->user()->can('view Year');
    }
//    public static function canCreate(): bool
//    {
//        if (auth()->user()->can('create Year')) {
//            return true;
//        }
//        return false;
//
//    }
//
//    public static function canEdit(Model $record): bool
//    {
//        if (auth()->user()->can('edit Year')) {
//            return true;
//        }
//        return false;
//    }
//
//    public static function canDelete(Model $record): bool
//    {
//        if (auth()->user()->can('delete Year')) {
//            return true;
//        }
//        return false;
//    }
////protected static bool $shouldRegisterNavigation = true;
//    public static function shouldRegisterNavigation(): bool
//    {
////        if (auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Management')) {
////            return true;
////        }
//        if (auth()->user()->can('view Year')) {
//            return true;
//        }
//        return true;
//    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('name')
                        ->label('Year')
                        ->unique(ignoreRecord: true),

//                    Forms\Components\DatePicker::make('start_date')
//                        ->closeOnDateSelection()->suffixIcon('heroicon-o-calendar')
//                        ->displayFormat('d-m-Y')
//                        ->label('Start date')
//                        ->unique(ignoreRecord: true)
//                        ->native(false),
//
//                    Forms\Components\DatePicker::make('end_date')
//                        ->closeOnDateSelection()->suffixIcon('heroicon-o-calendar')
//                        ->displayFormat('d-m-Y')
//                        ->label('End date')
//                        ->unique(ignoreRecord: true)
//                        ->native(false),
                    Select::make('country_id')->label('Countries')->multiple()
                        ->options(Country::pluck('name', 'id')->toArray())
                        ->default(Country::pluck('id')->toArray())
                        ->searchable(),
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
                        $newCountryIds = $record->country_id; // Ensure this is correctly set and not null.

                        $existingDocuments = Document::where('calendar_year_id', $record->id)->get();
                        $existingCountryIds = $existingDocuments->pluck('country_id')->toArray();
////                        dd($existingCountryIds);
//
//                        // Find country IDs to add and to remove
                        $toAdd = array_diff($newCountryIds, $existingCountryIds);
                        $toRemove = array_diff($existingCountryIds, $newCountryIds);
//                        dd($newCountryIds,$existingDocuments,$existingCountryIds,$toAdd,$toRemove);


                        DB::beginTransaction();
                        try {
                            foreach ($newCountryIds as $countryId) {
                                $entityIds = Entity::where('country_id', $countryId)->pluck('id')->toArray(); // Collect entity IDs
//                                dd($entityIds);
                                $country = Country::find($countryId); // Get the country details

                                if (!$country) {
                                    throw new \Exception("Country not found with ID: {$countryId}");
                                }

                                // Update or create the document
                                $document = Document::updateOrCreate(
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                    ],
                                    [
                                        'name' => $country->name,
                                        'entity_id' => $entityIds, // Ensure entity_id is stored as a JSON string if multiple IDs are expected
                                    ]
                                );
                                foreach ($entityIds as $entityId) {
                                    // Update or create compliance menus and submenus as needed
                                    $complianceMenu = ComplianceMenu::updateOrCreate(
                                        [
                                            'document_id' => $document->id,
                                            'entity_id' => $entityId,
                                        ],
                                        [
                                            'calendar_year_id' => $record->id,
                                            'country_id' => $countryId,
                                            'year' => $record->name,
                                        ]
                                    );

                                    $subMenuNames = ['Operations', 'Finance', 'HR'];
                                    foreach ($subMenuNames as $subMenuName) {
                                        ComplianceSubMenu::updateOrCreate(
                                            [
                                                'document_id' => $document->id,
                                                'sub_menu_name' => $subMenuName,
                                                'entity_id' => $entityId,
                                            ],
                                            [
                                                'calendar_year_id' => $record->id,
                                                'country_id' => $countryId,
                                                'year' => $record->name,
                                                'compliance_menu_id' => $complianceMenu->id,
                                            ]
                                        );
                                    }
                                }

                                // Add other related operations here if needed
                            }
                            DB::commit(); // Commit the transaction
                        } catch (\Exception $e) {
                            DB::rollBack(); // Roll back the transaction on error
                            Log::error("An error occurred while updating documents: " . $e->getMessage());
                            // Optionally rethrow the exception if you want it to be handled by the caller
                            throw $e;
                        }

                        foreach ($toRemove as $countryId) {
                            $document = Document::where('calendar_year_id', $record->id)->where('country_id', $countryId)->first();
                            if ($document) {
//                                dd($document);

                                $complianceMenu = ComplianceMenu::where('document_id', $document->id)->delete();
                                $complianceSubMenu = ComplianceSubMenu::where('document_id', $document->id)->delete();
                                $compliancePrimarySubMenu = CompliancePrimarySubMenu::where('document_id', $document->id)->delete();
//                                $complianceUploadDocument = UploadDocument::where('document_id', $document->id)->delete();
                                // Assuming you have cascading deletes set up in your database or you manually delete related records
                                $document->delete();
                            }
                        }
                    })

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
