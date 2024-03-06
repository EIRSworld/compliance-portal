<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ComplianceSummary;
use App\Filament\Widgets\ComplianceEventSummary;
use Filament\Pages\Dashboard as BasePage;

class Dashboard extends BasePage
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.dashboard';

    public function getColumns(): int|array
    {
        return 2;
    }

    public function getWidgets(): array
    {
        return [

            ComplianceSummary::class,
//            ComplianceEventSummary::class,
        ];
    }
}
