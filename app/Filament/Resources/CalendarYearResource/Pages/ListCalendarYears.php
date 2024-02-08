<?php

namespace App\Filament\Resources\CalendarYearResource\Pages;

use App\Filament\Resources\CalendarYearResource;
use App\Models\CalendarYear;
use App\Models\ComplianceMenu;
use App\Models\ComplianceSubMenu;
use App\Models\Country;
use App\Models\Document;
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
                $countryIds = $record->country_id;
                foreach($countryIds as $countryId){
//                dd($record->id);
                    // Compliance docs with due dates
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

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = 'COMPANY REGISTRATION';
                    $compliantSubMenu->save();

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = 'LICENCE';
                    $compliantSubMenu->save();

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = 'BROKER QTR SUBMISSIONS';
                    $compliantSubMenu->save();

                    // Agencies
                    $compliantMenu = new ComplianceMenu();
                    $compliantMenu->calendar_year_id = $record->id;
                    $compliantMenu->document_id = $document->id;
                    $compliantMenu->name = 'Agencies';
                    $compliantMenu->save();

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = 'Application Forms';
                    $compliantSubMenu->save();

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = 'KYC Docs';
                    $compliantSubMenu->save();

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = 'Onboarding Approval';
                    $compliantSubMenu->save();

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = "TOBA's";
                    $compliantSubMenu->save();

                    // Bank Account
                    $compliantMenu = new ComplianceMenu();
                    $compliantMenu->calendar_year_id = $record->id;
                    $compliantMenu->document_id = $document->id;
                    $compliantMenu->name = 'Bank Account';
                    $compliantMenu->save();

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = 'Application Forms';
                    $compliantSubMenu->save();

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = 'Bank Confirmation Docs';
                    $compliantSubMenu->save();

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = 'Proof of Captial Deposited';
                    $compliantSubMenu->save();

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = "Signatories";
                    $compliantSubMenu->save();

                    // Clients by BDM
                    $compliantMenu = new ComplianceMenu();
                    $compliantMenu->calendar_year_id = $record->id;
                    $compliantMenu->document_id = $document->id;
                    $compliantMenu->name = 'Clients by BDM';
                    $compliantMenu->save();

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = 'Approvals or Consent Forms';
                    $compliantSubMenu->save();

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = 'KYC Docs';
                    $compliantSubMenu->save();

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = 'Policy Schedules';
                    $compliantSubMenu->save();

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = "Remainder for Renewal";
                    $compliantSubMenu->save();

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = "Written Communication";
                    $compliantSubMenu->save();

                    // Finance
                    $compliantMenu = new ComplianceMenu();
                    $compliantMenu->calendar_year_id = $record->id;
                    $compliantMenu->document_id = $document->id;
                    $compliantMenu->name = 'Finance';
                    $compliantMenu->save();

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = 'Bank Statements';
                    $compliantSubMenu->save();

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = 'Expenses';
                    $compliantSubMenu->save();

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = 'Income';
                    $compliantSubMenu->save();

                    // Insurance Regulation Registration
                    $compliantMenu = new ComplianceMenu();
                    $compliantMenu->calendar_year_id = $record->id;
                    $compliantMenu->document_id = $document->id;
                    $compliantMenu->name = 'Insurance Regulation Registration';
                    $compliantMenu->save();

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = 'Licence';
                    $compliantSubMenu->save();

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = 'PI Cover';
                    $compliantSubMenu->save();

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = 'Remainder for Renewal';
                    $compliantSubMenu->save();


                    // Registration Docs
                    $compliantMenu = new ComplianceMenu();
                    $compliantMenu->calendar_year_id = $record->id;
                    $compliantMenu->document_id = $document->id;
                    $compliantMenu->name = 'Registration Docs';
                    $compliantMenu->save();

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = 'Business Plan';
                    $compliantSubMenu->save();

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = 'COC';
                    $compliantSubMenu->save();

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = 'Director Info';
                    $compliantSubMenu->save();

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = 'Projected Financials';
                    $compliantSubMenu->save();

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = 'Share Capital Docs';
                    $compliantSubMenu->save();

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = 'Share Certificate';
                    $compliantSubMenu->save();

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = 'Shareholder Info';
                    $compliantSubMenu->save();

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = 'SOP';
                    $compliantSubMenu->save();

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = 'Structure';
                    $compliantSubMenu->save();

                    // Reporting
                    $compliantMenu = new ComplianceMenu();
                    $compliantMenu->calendar_year_id = $record->id;
                    $compliantMenu->document_id = $document->id;
                    $compliantMenu->name = 'Reporting';
                    $compliantMenu->save();

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = 'LPM';
                    $compliantSubMenu->save();

                    $compliantSubMenu = new ComplianceSubMenu();
                    $compliantSubMenu->calendar_year_id = $record->id;
                    $compliantSubMenu->year = $record->name;
                    $compliantSubMenu->document_id = $document->id;
                    $compliantSubMenu->country_id = $countryId;
                    $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
                    $compliantSubMenu->name = 'Minutes of Meeting';
                    $compliantSubMenu->save();
                }
            }),
        ];
    }
}
