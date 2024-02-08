<?php

namespace App\Filament\Resources\CountryResource\Pages;

use App\Filament\Resources\CountryResource;
use App\Models\CalendarYear;
use App\Models\ComplianceMenu;
use App\Models\ComplianceSubMenu;
use App\Models\Country;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCountries extends ListRecords
{
    protected static string $resource = CountryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Create Country')->modalWidth('sm')->createAnother(false)
//            ->after(function (Country $record){
//
////                $currentYear = Carbon::now()->format('Y');
////                $calendarYear = CalendarYear::whereName($currentYear)->first();
//                // Compliance docs with due dates
//                $compliantMenu = new ComplianceMenu();
//                $compliantMenu->country_id = $record->id;
//                $compliantMenu->name = 'Compliance docs with due dates';
//                $compliantMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = 'PI COVER/3RD PARTY COVER';
//                $compliantSubMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = 'AUDITED FINANCIALS';
//                $compliantSubMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = 'COMPANY REGISTRATION';
//                $compliantSubMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = 'LICENCE';
//                $compliantSubMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = 'BROKER QTR SUBMISSIONS';
//                $compliantSubMenu->save();
//
//                // Agencies
//                $compliantMenu = new ComplianceMenu();
//                $compliantMenu->country_id = $record->id;
//                $compliantMenu->name = 'Agencies';
//                $compliantMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = 'Application Forms';
//                $compliantSubMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = 'KYC Docs';
//                $compliantSubMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = 'Onboarding Approval';
//                $compliantSubMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = "TOBA's";
//                $compliantSubMenu->save();
//
//                // Bank Account
//                $compliantMenu = new ComplianceMenu();
//                $compliantMenu->country_id = $record->id;
//                $compliantMenu->name = 'Bank Account';
//                $compliantMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = 'Application Forms';
//                $compliantSubMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = 'Bank Confirmation Docs';
//                $compliantSubMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = 'Proof of Captial Deposited';
//                $compliantSubMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = "Signatories";
//                $compliantSubMenu->save();
//
//                // Clients by BDM
//                $compliantMenu = new ComplianceMenu();
//                $compliantMenu->country_id = $record->id;
//                $compliantMenu->name = 'Clients by BDM';
//                $compliantMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = 'Approvals or Consent Forms';
//                $compliantSubMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = 'KYC Docs';
//                $compliantSubMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = 'Policy Schedules';
//                $compliantSubMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = "Remainder for Renewal";
//                $compliantSubMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = "Written Communication";
//                $compliantSubMenu->save();
//
//                // Finance
//                $compliantMenu = new ComplianceMenu();
//                $compliantMenu->country_id = $record->id;
//                $compliantMenu->name = 'Finance';
//                $compliantMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = 'Bank Statements';
//                $compliantSubMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = 'Expenses';
//                $compliantSubMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = 'Income';
//                $compliantSubMenu->save();
//
//                // Insurance Regulation Registration
//                $compliantMenu = new ComplianceMenu();
//                $compliantMenu->country_id = $record->id;
//                $compliantMenu->name = 'Insurance Regulation Registration';
//                $compliantMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = 'Licence';
//                $compliantSubMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = 'PI Cover';
//                $compliantSubMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = 'Remainder for Renewal';
//                $compliantSubMenu->save();
//
//                // Registration Docs
//                $compliantMenu = new ComplianceMenu();
//                $compliantMenu->country_id = $record->id;
//                $compliantMenu->name = 'Registration Docs';
//                $compliantMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = 'Business Plan';
//                $compliantSubMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = 'COC';
//                $compliantSubMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = 'Director Info';
//                $compliantSubMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = 'Projected Financials';
//                $compliantSubMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = 'Share Capital Docs';
//                $compliantSubMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = 'Share Certificate';
//                $compliantSubMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = 'Shareholder Info';
//                $compliantSubMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = 'SOP';
//                $compliantSubMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = 'Structure';
//                $compliantSubMenu->save();
//
//                // Reporting
//                $compliantMenu = new ComplianceMenu();
//                $compliantMenu->country_id = $record->id;
//                $compliantMenu->name = 'Reporting';
//                $compliantMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = 'LPM';
//                $compliantSubMenu->save();
//
//                $compliantSubMenu = new ComplianceSubMenu();
//                $compliantSubMenu->country_id = $record->id;
//                $compliantSubMenu->compliance_menu_id = $compliantMenu->id;
//                $compliantSubMenu->name = 'Minutes of Meeting';
//                $compliantSubMenu->save();
//
//
//            }),
        ];
    }
}
