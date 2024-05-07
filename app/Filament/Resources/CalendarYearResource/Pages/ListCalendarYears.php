<?php

namespace App\Filament\Resources\CalendarYearResource\Pages;

use App\Filament\Resources\CalendarYearResource;
use App\Models\CalendarYear;
use App\Models\ComplianceMenu;
use App\Models\CompliancePrimarySubMenu;
use App\Models\ComplianceSubMenu;
use App\Models\Country;
use App\Models\Document;
use App\Models\Entity;
use App\Models\UploadDocument;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Log;

class ListCalendarYears extends ListRecords
{
    protected static string $resource = CalendarYearResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Create Year')->modalWidth('md')->createAnother(false)
                ->after(function (CalendarYear $record) {
                    try {
                        $countryIds = $record->country_id;
                        foreach ($countryIds as $countryId) {
                            $entityIds = Entity::where('country_id', $countryId)->pluck('id');


                                $country = Country::find($countryId);

                                    $document = new Document();
                                    $document->name = $country->name;
                                    $document->country_id = $countryId;
                                    $document->entity_id = $entityIds;
                                    $document->calendar_year_id = $record->id;
                                    $document->save();
                                    foreach ($entityIds as $entityId) {
                                        $complianceMenu = new ComplianceMenu();
                                        $complianceMenu->calendar_year_id = $record->id;
                                        $complianceMenu->document_id = $document->id;
                                        $complianceMenu->country_id = $countryId;
                                        $complianceMenu->entity_id = $entityId;
                                        $complianceMenu->year = $record->name;
                                        $complianceMenu->save();

                                        $compliantSubMenu = new ComplianceSubMenu();
                                        $compliantSubMenu->calendar_year_id = $record->id;
                                        $compliantSubMenu->year = $record->name;
                                        $compliantSubMenu->document_id = $document->id;
                                        $compliantSubMenu->country_id = $countryId;
                                        $compliantSubMenu->entity_id = $entityId;
                                        $compliantSubMenu->compliance_menu_id = $complianceMenu->id;
                                        $compliantSubMenu->sub_menu_name = 'Operations';
                                        $compliantSubMenu->save();

                                        $compliantSubMenu = new ComplianceSubMenu();
                                        $compliantSubMenu->calendar_year_id = $record->id;
                                        $compliantSubMenu->year = $record->name;
                                        $compliantSubMenu->document_id = $document->id;
                                        $compliantSubMenu->country_id = $countryId;
                                        $compliantSubMenu->entity_id = $entityId;
                                        $compliantSubMenu->compliance_menu_id = $complianceMenu->id;
                                        $compliantSubMenu->sub_menu_name = 'Finance';
                                        $compliantSubMenu->save();

                                        $compliantSubMenu = new ComplianceSubMenu();
                                        $compliantSubMenu->calendar_year_id = $record->id;
                                        $compliantSubMenu->year = $record->name;
                                        $compliantSubMenu->document_id = $document->id;
                                        $compliantSubMenu->country_id = $countryId;
                                        $compliantSubMenu->entity_id = $entityId;
                                        $compliantSubMenu->compliance_menu_id = $complianceMenu->id;
                                        $compliantSubMenu->sub_menu_name = 'HR';
                                        $compliantSubMenu->save();

                                    }

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
