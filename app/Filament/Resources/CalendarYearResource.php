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

class CalendarYearResource extends Resource
{
    protected static ?string $model = CalendarYear::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Masters';
    protected static ?string $navigationLabel = 'Calendar Years';
    protected static ?int $navigationSort = 1;
    protected static ?string $label = 'Calendar Years';

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

                        // Handle new countries
                        try {
                            foreach ($newCountryIds as $countryId) {
                                // Find or create the document
                                $document = Document::updateOrCreate(
                                    ['calendar_year_id' => $record->id, 'country_id' => $countryId],
                                    ['name' => Country::find($countryId)->name]
                                );

                                // Update or create ComplianceMenu for "Agencies"
                                ComplianceMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Agencies'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'folder_type' => 'Sub Folder',
                                    ]
                                );

                                // Update or create ComplianceMenu for "Finance"
                                ComplianceMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Finance'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'folder_type' => 'Sub Folder',
                                    ]
                                );

                                // Update or create a ComplianceSubMenu under "Finance"
                                ComplianceSubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'sub_menu_name' => 'Bank Statements'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'folder_type' => 'Sub Folder',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'Jan'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Bank Statements')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Jan'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Bank Statements')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'Jan')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'Feb'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Bank Statements')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Feb'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Bank Statements')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'Feb')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'Mar'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Bank Statements')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Mar'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Bank Statements')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'Mar')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'Apr'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Bank Statements')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Apr'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Bank Statements')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'Apr')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'May'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Bank Statements')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'May'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Bank Statements')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'May')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'June'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Bank Statements')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'June'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Bank Statements')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'June')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'July'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Bank Statements')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'July'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Bank Statements')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'July')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'Aug'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Bank Statements')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Aug'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Bank Statements')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'Aug')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'Sep'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Bank Statements')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Sep'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Bank Statements')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'Sep')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'Oct'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Bank Statements')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Oct'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Bank Statements')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'Oct')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'Nov'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Bank Statements')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Nov'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Bank Statements')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'Nov')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'Dec'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Bank Statements')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Dec'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Bank Statements')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'Dec')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                // Update or create a ComplianceSubMenu under "Finance"
                                ComplianceSubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'sub_menu_name' => 'Expenses'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'folder_type' => 'Sub Folder',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'Jan'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Expenses')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Jan'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Expenses')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'Jan')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'Feb'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Expenses')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Feb'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Expenses')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'Feb')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'Mar'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Expenses')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Mar'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Expenses')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'Mar')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'Apr'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Expenses')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Apr'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Expenses')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'Apr')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'May'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Expenses')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'May'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Expenses')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'May')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'June'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Expenses')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'June'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Expenses')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'June')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'July'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Expenses')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'July'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Expenses')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'July')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'Aug'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Expenses')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Aug'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Expenses')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'Aug')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'Sep'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Expenses')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Sep'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Expenses')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'Sep')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'Oct'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Expenses')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Oct'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Expenses')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'Oct')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'Nov'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Expenses')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Nov'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Expenses')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'Nov')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'Dec'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Expenses')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Dec'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Expenses')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'Dec')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                // Update or create a ComplianceSubMenu under "Finance"
                                ComplianceSubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'sub_menu_name' => 'Income'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'folder_type' => 'Sub Folder',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'Jan'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Income')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Jan'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Income')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'Jan')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'Feb'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Income')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Feb'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Income')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'Feb')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'Mar'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Income')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Mar'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Income')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'Mar')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'Apr'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Income')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Apr'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Income')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'Apr')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'May'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Income')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'May'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Income')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'May')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'June'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Income')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'June'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Income')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'June')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'July'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Income')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'July'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Income')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'July')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'Aug'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Income')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Aug'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Income')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'Aug')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'Sep'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Income')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Sep'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Income')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'Sep')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'Oct'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Income')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Oct'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Income')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'Oct')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'Nov'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Income')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Nov'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Income')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'Nov')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'Dec'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Income')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Dec'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Income')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'Dec')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                // Update or create a ComplianceSubMenu under "Finance"
                                ComplianceSubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'sub_menu_name' => 'Annual Return'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_expired' => 1,
                                        'expired_date' => Carbon::now(),
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Annual Return'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Annual Return')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_expired' => 1,
                                        'expired_date' => Carbon::now(),
                                        'is_uploaded' => 0,
                                    ]
                                );

                                ComplianceSubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'sub_menu_name' => 'Audited Financials'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_expired' => 1,
                                        'expired_date' => Carbon::now(),
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Audited Financials'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Audited Financials')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_expired' => 1,
                                        'expired_date' => Carbon::now(),
                                        'is_uploaded' => 0,
                                    ]
                                );

                                ComplianceSubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'sub_menu_name' => 'Broker Qtr Submissions'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'folder_type' => 'Sub Folder',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                $year = $record->name;
                                $endOfMarchForYear = Carbon::create($year, 3)->endOfMonth();
                                $endOfJuneForYear = Carbon::create($year, 6)->endOfMonth();
                                $endOfSepForYear = Carbon::create($year, 9)->endOfMonth();
                                $endOfDecForYear = Carbon::create($year, 12)->endOfMonth();

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => '1st Qtr'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Broker Qtr Submissions')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_expired' => 1,
                                        'expired_date' => $endOfMarchForYear,
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => '1st Qtr'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Broker Qtr Submissions')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', '1st Qtr')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_expired' => 1,
                                        'expired_date' => $endOfMarchForYear,
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => '2nd Qtr'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Broker Qtr Submissions')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_expired' => 1,
                                        'expired_date' => $endOfJuneForYear,
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => '2nd Qtr'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Broker Qtr Submissions')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', '2nd Qtr')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_expired' => 1,
                                        'expired_date' => $endOfJuneForYear,
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => '3rd Qtr'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Broker Qtr Submissions')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_expired' => 1,
                                        'expired_date' => $endOfSepForYear,
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => '3rd Qtr'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Broker Qtr Submissions')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', '3rd Qtr')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_expired' => 1,
                                        'expired_date' => $endOfSepForYear,
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => '4th Qtr'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Broker Qtr Submissions')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_expired' => 1,
                                        'expired_date' => $endOfDecForYear,
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => '4th Qtr'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Finance')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Broker Qtr Submissions')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', '4th Qtr')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_expired' => 1,
                                        'expired_date' => $endOfDecForYear,
                                        'is_uploaded' => 0,
                                    ]
                                );

                                ComplianceMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Client Documents'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'folder_type' => 'Sub Folder',
                                    ]
                                );

                                ComplianceMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Company Registration'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'folder_type' => 'Sub Folder',
                                    ]
                                );


                                ComplianceSubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'sub_menu_name' => 'SOP'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Company Registration')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'SOP'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Company Registration')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'SOP')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                ComplianceSubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'sub_menu_name' => 'Certificate of Corporation'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Company Registration')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Certificate of Corporation'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Company Registration')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Certificate of Corporation')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                ComplianceSubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'sub_menu_name' => 'Shareholder Documents'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Company Registration')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Shareholder Documents'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Company Registration')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Shareholder Documents')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                ComplianceSubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'sub_menu_name' => 'Director Documents'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Company Registration')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Director Documents'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Company Registration')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Director Documents')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                ComplianceSubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'sub_menu_name' => 'PI/Fidelity/3rd Party Cover'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Company Registration')->value('id'),
                                        'folder_type' => 'Sub Folder',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'PI Cover'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Company Registration')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'PI/Fidelity/3rd Party Cover')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_expired' => 1,
                                        'expired_date' => Carbon::now(),
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'PI Cover'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Company Registration')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'PI/Fidelity/3rd Party Cover')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'PI Cover')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_expired' => 1,
                                        'expired_date' => Carbon::now(),
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'Fidelity'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Company Registration')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'PI/Fidelity/3rd Party Cover')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_expired' => 1,
                                        'expired_date' => Carbon::now(),
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Fidelity'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Company Registration')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'PI/Fidelity/3rd Party Cover')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'Fidelity')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_expired' => 1,
                                        'expired_date' => Carbon::now(),
                                        'is_uploaded' => 0,
                                    ]
                                );


                                ComplianceSubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'sub_menu_name' => 'VAT Registration Documents'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Company Registration')->value('id'),
                                        'folder_type' => 'Sub Folder',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'VAT'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Company Registration')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'VAT Registration Documents')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'VAT'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Company Registration')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'VAT Registration Documents')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'VAT')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                ComplianceSubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'sub_menu_name' => 'Tax Registration Documents'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Company Registration')->value('id'),
                                        'folder_type' => 'Sub Folder',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'Tax'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Company Registration')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Tax Registration Documents')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Tax'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Company Registration')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Tax Registration Documents')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'Tax')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );


                                ComplianceSubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'sub_menu_name' => 'Public Officer Documents'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Company Registration')->value('id'),
                                        'folder_type' => 'Sub Folder',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                ComplianceSubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'sub_menu_name' => 'Bank Account'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Company Registration')->value('id'),
                                        'folder_type' => 'Sub Folder',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                ComplianceSubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'sub_menu_name' => 'Registration Documents'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Company Registration')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_expired' => 1,
                                        'expired_date' => Carbon::now(),
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Registration Documents'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Company Registration')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Registration Documents')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_expired' => 1,
                                        'expired_date' => Carbon::now(),
                                        'is_uploaded' => 0,
                                    ]
                                );


                                ComplianceMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Employee Documents'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'folder_type' => 'Sub Folder',
                                    ]
                                );

                                ComplianceMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Licence'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'folder_type' => 'Sub Folder',
                                    ]
                                );

                                ComplianceSubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'sub_menu_name' => 'Licence Registration Documents'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Licence')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Licence Registration Documents'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Licence')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Licence Registration Documents')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_uploaded' => 0,
                                    ]
                                );



                                ComplianceSubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'sub_menu_name' => 'Licence Certificate'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Licence')->value('id'),
                                        'folder_type' => 'Sub Folder',
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'Proof of Payment'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Licence')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Licence Certificate')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_expired' => 1,
                                        'expired_date' => Carbon::now(),
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Proof of Payment'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Licence')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Licence Certificate')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'Proof of Payment')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_expired' => 1,
                                        'expired_date' => Carbon::now(),
                                        'is_uploaded' => 0,
                                    ]
                                );

                                CompliancePrimarySubMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'primary_name' => 'Certificate'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Licence')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Licence Certificate')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_expired' => 1,
                                        'expired_date' => Carbon::now(),
                                        'is_uploaded' => 0,
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Certificate'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Licence')->value('id'),
                                        'compliance_sub_menu_id' => ComplianceSubMenu::where('document_id', $document->id)->where('sub_menu_name', 'Licence Certificate')->value('id'),
                                        'compliance_primary_sub_menu_id' => CompliancePrimarySubMenu::where('document_id', $document->id)->where('primary_name', 'Certificate')->value('id'),
                                        'folder_type' => 'Upload',
                                        'is_expired' => 1,
                                        'expired_date' => Carbon::now(),
                                        'is_uploaded' => 0,
                                    ]
                                );


                                ComplianceMenu::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Internal Policies'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'folder_type' => 'Upload',
                                    ]
                                );
                                UploadDocument::updateOrCreate(
                                    ['document_id' => $document->id, 'name' => 'Internal Policies'],
                                    [
                                        'calendar_year_id' => $record->id,
                                        'country_id' => $countryId,
                                        'year' => $record->name,
                                        'compliance_menu_id' => ComplianceMenu::where('document_id', $document->id)->where('name', 'Internal Policies')->value('id'),
                                        'folder_type' => 'Upload',
                                    ]
                                );

                            }
                        } catch (\Exception $e) {
                            // Handle the exception
                            echo "An error occurred: " . $e->getMessage();
                            // Optionally, log the error or take other actions.
                        }

                        // Handle removal of countries and their documents
                        foreach ($toRemove as $countryId) {
                            $document = Document::where('calendar_year_id', $record->id)->where('country_id', $countryId)->first();
                            if ($document) {
//                                dd($document);

                                $complianceMenu = ComplianceMenu::where('document_id', $document->id)->delete();
                                $complianceSubMenu = ComplianceSubMenu::where('document_id', $document->id)->delete();
                                $compliancePrimarySubMenu = CompliancePrimarySubMenu::where('document_id', $document->id)->delete();
                                $complianceUploadDocument = UploadDocument::where('document_id', $document->id)->delete();
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
