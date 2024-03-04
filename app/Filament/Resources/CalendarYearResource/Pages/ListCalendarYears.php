<?php

namespace App\Filament\Resources\CalendarYearResource\Pages;

use App\Filament\Resources\CalendarYearResource;
use App\Models\CalendarYear;
use App\Models\ComplianceMenu;
use App\Models\CompliancePrimarySubMenu;
use App\Models\ComplianceSubMenu;
use App\Models\Country;
use App\Models\Document;
use App\Models\UploadDocument;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCalendarYears extends ListRecords
{
    protected static string $resource = CalendarYearResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Create Year')->modalWidth('md')->createAnother(false)
            ->after(function (CalendarYear $record){
                try {
                    $countryIds = $record->country_id;
                    foreach ($countryIds as $countryId) {
//                dd($record->id);
                        // Compliance docs with due dates
                        $document = new Document();
                        $country = Country::find($countryId);
                        $document->country_id = $countryId;
                        $document->calendar_year_id = $record->id;
                        $document->name = $country->name;
                        $document->save();

                        // Agencies
                        $compliantMenu = new ComplianceMenu();
                        $compliantMenu->calendar_year_id = $record->id;
                        $compliantMenu->document_id = $document->id;
                        $compliantMenu->country_id = $countryId;
                        $compliantMenu->year = $record->name;
                        $compliantMenu->name = 'Agencies';
                        $compliantMenu->folder_type = 'Sub Folder';
                        $compliantMenu->save();


                        // Finance
                        $compliantMenu = new ComplianceMenu();
                        $compliantMenu->calendar_year_id = $record->id;
                        $compliantMenu->document_id = $document->id;
                        $compliantMenu->country_id = $countryId;
                        $compliantMenu->year = $record->name;
                        $compliantMenu->name = 'Finance';
                        $compliantMenu->folder_type = 'Sub Folder';
                        $compliantMenu->save();

                        $compliantSubMenu = new ComplianceSubMenu();
                        $compliantSubMenu->calendar_year_id = $record->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->year = $record->name;
                        $compliantSubMenu->document_id = $document->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliantSubMenu->sub_menu_name = 'Bank Statements';
                        $compliantSubMenu->folder_type = 'Sub Folder';
                        $compliantSubMenu->is_uploaded = 0;
                        $compliantSubMenu->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'Jan';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'Jan';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'Feb';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'Feb';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'Mar';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'Mar';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'Apr';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'Apr';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'May';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'May';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'June';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'June';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'July';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'July';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'Aug';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'Aug';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'Sep';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'Sep';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'Oct';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'Oct';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'Nov';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'Nov';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'Dec';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'Dec';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliantSubMenu = new ComplianceSubMenu();
                        $compliantSubMenu->calendar_year_id = $record->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->year = $record->name;
                        $compliantSubMenu->document_id = $document->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliantSubMenu->sub_menu_name = 'Expenses';
                        $compliantSubMenu->folder_type = 'Sub Folder';
                        $compliantSubMenu->is_uploaded = 0;
                        $compliantSubMenu->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'Jan';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'Jan';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'Feb';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'Feb';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'Mar';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'Mar';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'Apr';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'Apr';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'May';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'May';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'June';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'June';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'July';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'July';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'Aug';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'Aug';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'Sep';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'Sep';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'Oct';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'Oct';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'Nov';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'Nov';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'Dec';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'Dec';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliantSubMenu = new ComplianceSubMenu();
                        $compliantSubMenu->calendar_year_id = $record->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->year = $record->name;
                        $compliantSubMenu->document_id = $document->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliantSubMenu->sub_menu_name = 'Income';
                        $compliantSubMenu->folder_type = 'Sub Folder';
                        $compliantSubMenu->is_uploaded = 0;
                        $compliantSubMenu->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'Jan';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'Jan';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'Feb';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'Feb';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'Mar';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'Mar';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'Apr';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'Apr';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'May';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'May';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'June';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'June';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'July';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'July';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'Aug';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'Aug';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'Sep';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'Sep';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'Oct';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'Oct';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'Nov';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'Nov';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'Dec';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'Dec';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();


                        $compliantSubMenu = new ComplianceSubMenu();
                        $compliantSubMenu->calendar_year_id = $record->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->year = $record->name;
                        $compliantSubMenu->document_id = $document->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliantSubMenu->sub_menu_name = 'Annual Return';
                        $compliantSubMenu->is_expired = 1;
                        $compliantSubMenu->expired_date = Carbon::now();
                        $compliantSubMenu->folder_type = 'Upload';
                        $compliantSubMenu->is_uploaded = 0;
                        $compliantSubMenu->save();


                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->name = 'Annual Return';
                        $complianceUploadDocument->is_expired = 1;
                        $complianceUploadDocument->expired_date = Carbon::now();
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliantSubMenu = new ComplianceSubMenu();
                        $compliantSubMenu->calendar_year_id = $record->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->year = $record->name;
                        $compliantSubMenu->document_id = $document->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliantSubMenu->sub_menu_name = 'Audited Financials';
                        $compliantSubMenu->is_expired = 1;
                        $compliantSubMenu->expired_date = Carbon::now();
                        $compliantSubMenu->folder_type = 'Upload';
                        $compliantSubMenu->is_uploaded = 0;
                        $compliantSubMenu->save();


                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->name = 'Audited Financials';
                        $complianceUploadDocument->is_expired = 1;
                        $complianceUploadDocument->expired_date = Carbon::now();
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliantSubMenu = new ComplianceSubMenu();
                        $compliantSubMenu->calendar_year_id = $record->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->year = $record->name;
                        $compliantSubMenu->document_id = $document->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliantSubMenu->sub_menu_name = 'Broker Qtr Submissions';
                        $compliantSubMenu->folder_type = 'Sub Folder';
                        $compliantSubMenu->is_uploaded = 0;
                        $compliantSubMenu->save();


                        $year = $record->name;
                        $endOfMarchForYear = Carbon::create($year, 3)->endOfMonth();
                        $endOfJuneForYear = Carbon::create($year, 6)->endOfMonth();
                        $endOfSepForYear = Carbon::create($year, 9)->endOfMonth();
                        $endOfDecForYear = Carbon::create($year, 12)->endOfMonth();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = '1st Qtr';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_expired = 1;
                        $compliancePrimarySubMenu->expired_date = $endOfMarchForYear;
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = '1st Qtr';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_expired = 1;
                        $complianceUploadDocument->expired_date = $endOfMarchForYear;
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();


                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = '2nd Qtr';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_expired = 1;
                        $compliancePrimarySubMenu->expired_date = $endOfJuneForYear;
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = '2nd Qtr';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_expired = 1;
                        $complianceUploadDocument->expired_date = $endOfJuneForYear;
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = '3rd Qtr';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_expired = 1;
                        $compliancePrimarySubMenu->expired_date = $endOfSepForYear;
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = '3rd Qtr';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_expired = 1;
                        $complianceUploadDocument->expired_date = $endOfSepForYear;
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = '4th Qtr';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_expired = 1;
                        $compliancePrimarySubMenu->expired_date = $endOfDecForYear;
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = '4th Qtr';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_expired = 1;
                        $complianceUploadDocument->expired_date = $endOfDecForYear;
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        // Client Documents
                        $compliantMenu = new ComplianceMenu();
                        $compliantMenu->calendar_year_id = $record->id;
                        $compliantMenu->document_id = $document->id;
                        $compliantMenu->country_id = $countryId;
                        $compliantMenu->year = $record->name;
                        $compliantMenu->name = 'Client Documents';
                        $compliantMenu->folder_type = 'Sub Folder';
                        $compliantMenu->save();

                        // Company Registration
                        $compliantMenu = new ComplianceMenu();
                        $compliantMenu->calendar_year_id = $record->id;
                        $compliantMenu->document_id = $document->id;
                        $compliantMenu->country_id = $countryId;
                        $compliantMenu->year = $record->name;
                        $compliantMenu->name = 'Company Registration';
                        $compliantMenu->folder_type = 'Sub Folder';
                        $compliantMenu->save();

                        $compliantSubMenu = new ComplianceSubMenu();
                        $compliantSubMenu->calendar_year_id = $record->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->year = $record->name;
                        $compliantSubMenu->document_id = $document->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliantSubMenu->sub_menu_name = 'SOP';
                        $compliantSubMenu->folder_type = 'Upload';
                        $compliantSubMenu->is_uploaded = 0;
                        $compliantSubMenu->save();


                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->name = 'SOP';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliantSubMenu = new ComplianceSubMenu();
                        $compliantSubMenu->calendar_year_id = $record->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->year = $record->name;
                        $compliantSubMenu->document_id = $document->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliantSubMenu->sub_menu_name = 'Certificate of Corporation';
                        $compliantSubMenu->folder_type = 'Upload';
                        $compliantSubMenu->is_uploaded = 0;
                        $compliantSubMenu->save();


                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->name = 'Certificate of Corporation';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliantSubMenu = new ComplianceSubMenu();
                        $compliantSubMenu->calendar_year_id = $record->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->year = $record->name;
                        $compliantSubMenu->document_id = $document->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliantSubMenu->sub_menu_name = 'Shareholder Documents';
                        $compliantSubMenu->folder_type = 'Upload';
                        $compliantSubMenu->is_uploaded = 0;
                        $compliantSubMenu->save();


                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->name = 'Shareholder Documents';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliantSubMenu = new ComplianceSubMenu();
                        $compliantSubMenu->calendar_year_id = $record->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->year = $record->name;
                        $compliantSubMenu->document_id = $document->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliantSubMenu->sub_menu_name = 'Director Documents';
                        $compliantSubMenu->folder_type = 'Upload';
                        $compliantSubMenu->is_uploaded = 0;
                        $compliantSubMenu->save();


                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->name = 'Director Documents';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();


                        $compliantSubMenu = new ComplianceSubMenu();
                        $compliantSubMenu->calendar_year_id = $record->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->year = $record->name;
                        $compliantSubMenu->document_id = $document->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliantSubMenu->sub_menu_name = 'PI/Fidelity/3rd Party Cover';
                        $compliantSubMenu->folder_type = 'Sub Folder';
                        $compliantSubMenu->is_uploaded = 0;
                        $compliantSubMenu->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'PI Cover';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_expired = 1;
                        $compliancePrimarySubMenu->expired_date = Carbon::now();
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'PI Cover';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_expired = 1;
                        $complianceUploadDocument->expired_date = Carbon::now();
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'Fidelity';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_expired = 1;
                        $compliancePrimarySubMenu->expired_date = Carbon::now();
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'Fidelity';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_expired = 1;
                        $complianceUploadDocument->expired_date = Carbon::now();
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliantSubMenu = new ComplianceSubMenu();
                        $compliantSubMenu->calendar_year_id = $record->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->year = $record->name;
                        $compliantSubMenu->document_id = $document->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliantSubMenu->sub_menu_name = 'VAT Registration Documents';
                        $compliantSubMenu->folder_type = 'Sub Folder';
                        $compliantSubMenu->is_uploaded = 0;
                        $compliantSubMenu->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'VAT';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'VAT';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliantSubMenu = new ComplianceSubMenu();
                        $compliantSubMenu->calendar_year_id = $record->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->year = $record->name;
                        $compliantSubMenu->document_id = $document->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliantSubMenu->sub_menu_name = 'Tax Registration Documents';
                        $compliantSubMenu->folder_type = 'Sub Folder';
                        $compliantSubMenu->is_uploaded = 0;
                        $compliantSubMenu->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'Tax';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'Tax';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliantSubMenu = new ComplianceSubMenu();
                        $compliantSubMenu->calendar_year_id = $record->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->year = $record->name;
                        $compliantSubMenu->document_id = $document->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliantSubMenu->sub_menu_name = 'Public Officer Documents';
                        $compliantSubMenu->folder_type = 'Sub Folder';
                        $compliantSubMenu->is_uploaded = 0;
                        $compliantSubMenu->save();

                        $compliantSubMenu = new ComplianceSubMenu();
                        $compliantSubMenu->calendar_year_id = $record->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->year = $record->name;
                        $compliantSubMenu->document_id = $document->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliantSubMenu->sub_menu_name = 'Bank Account';
                        $compliantSubMenu->folder_type = 'Sub Folder';
                        $compliantSubMenu->is_uploaded = 0;
                        $compliantSubMenu->save();

                        $compliantSubMenu = new ComplianceSubMenu();
                        $compliantSubMenu->calendar_year_id = $record->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->year = $record->name;
                        $compliantSubMenu->document_id = $document->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliantSubMenu->sub_menu_name = 'Registration Documents';
                        $compliantSubMenu->folder_type = 'Upload';
                        $compliantSubMenu->is_expired = 1;
                        $compliantSubMenu->expired_date = Carbon::now();
                        $compliantSubMenu->is_uploaded = 0;
                        $compliantSubMenu->save();


                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->name = 'Registration Documents';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_expired = 1;
                        $complianceUploadDocument->expired_date = Carbon::now();
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        // Employee Documents
                        $compliantMenu = new ComplianceMenu();
                        $compliantMenu->calendar_year_id = $record->id;
                        $compliantMenu->document_id = $document->id;
                        $compliantMenu->country_id = $countryId;
                        $compliantMenu->year = $record->name;
                        $compliantMenu->name = 'Employee Documents';
                        $compliantMenu->folder_type = 'Sub Folder';
                        $compliantMenu->save();

                        // Licence
                        $compliantMenu = new ComplianceMenu();
                        $compliantMenu->calendar_year_id = $record->id;
                        $compliantMenu->document_id = $document->id;
                        $compliantMenu->country_id = $countryId;
                        $compliantMenu->year = $record->name;
                        $compliantMenu->name = 'Licence';
                        $compliantMenu->folder_type = 'Sub Folder';
                        $compliantMenu->save();

                        $compliantSubMenu = new ComplianceSubMenu();
                        $compliantSubMenu->calendar_year_id = $record->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->year = $record->name;
                        $compliantSubMenu->document_id = $document->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliantSubMenu->sub_menu_name = 'Licence Registration Documents';
                        $compliantSubMenu->folder_type = 'Upload';
                        $compliantSubMenu->is_uploaded = 0;
                        $compliantSubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->name = 'Licence Registration Documents';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliantSubMenu = new ComplianceSubMenu();
                        $compliantSubMenu->calendar_year_id = $record->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->year = $record->name;
                        $compliantSubMenu->document_id = $document->id;
                        $compliantSubMenu->country_id = $countryId;
                        $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliantSubMenu->sub_menu_name = 'Licence Certificate';
                        $compliantSubMenu->folder_type = 'Sub Folder';
                        $compliantSubMenu->is_uploaded = 0;
                        $compliantSubMenu->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'Proof of Payment';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_expired = 1;
                        $compliancePrimarySubMenu->expired_date = Carbon::now();
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'Proof of Payment';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_expired = 1;
                        $compliancePrimarySubMenu->expired_date = Carbon::now();
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        $compliancePrimarySubMenu = new CompliancePrimarySubMenu();
                        $compliancePrimarySubMenu->country_id = $countryId;
                        $compliancePrimarySubMenu->document_id = $document->id;
                        $compliancePrimarySubMenu->compliance_menu_id = $compliantMenu->id;
                        $compliancePrimarySubMenu->compliance_sub_menu_id = $compliantSubMenu->id;
                        $compliancePrimarySubMenu->calendar_year_id = $record->id;
                        $compliancePrimarySubMenu->year = $record->name;
                        $compliancePrimarySubMenu->primary_name = 'Certificate';
                        $compliancePrimarySubMenu->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_expired = 1;
                        $compliancePrimarySubMenu->expired_date = Carbon::now();
                        $compliancePrimarySubMenu->is_uploaded = 0;
                        $compliancePrimarySubMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->compliance_sub_menu_id = $compliantSubMenu->id;
                        $complianceUploadDocument->compliance_primary_sub_menu_id = $compliancePrimarySubMenu->id;
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'Certificate';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $compliancePrimarySubMenu->is_expired = 1;
                        $compliancePrimarySubMenu->expired_date = Carbon::now();
                        $complianceUploadDocument->is_uploaded = 0;
                        $complianceUploadDocument->save();

                        // Internal Policies
                        $compliantMenu = new ComplianceMenu();
                        $compliantMenu->calendar_year_id = $record->id;
                        $compliantMenu->document_id = $document->id;
                        $compliantMenu->country_id = $countryId;
                        $compliantMenu->year = $record->name;
                        $compliantMenu->name = 'Internal Policies';
                        $compliantMenu->folder_type = 'Upload';
                        $compliantMenu->save();

                        $complianceUploadDocument = new UploadDocument();
                        $complianceUploadDocument->calendar_year_id = $record->id;
                        $complianceUploadDocument->document_id = $document->id;
                        $complianceUploadDocument->compliance_menu_id = $compliantMenu->id;
                        $complianceUploadDocument->country_id = $countryId;
                        $complianceUploadDocument->year = $record->name;
                        $complianceUploadDocument->name = 'Internal Policies';
                        $complianceUploadDocument->folder_type = 'Upload';
                        $complianceUploadDocument->save();

                    }
                } catch (\Exception $e) {
                    // Handle the exception
                    echo "An error occurred: " . $e->getMessage();
                    // Optionally, log the error or take other actions.
                }
            }),
        ];
    }
}
