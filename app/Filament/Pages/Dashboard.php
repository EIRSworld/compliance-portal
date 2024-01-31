<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ComplianceTotal;
use Filament\Pages\Dashboard as BasePage;
use Filament\Pages\Page;

class Dashboard extends BasePage
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.dashboard';




    public function getWidgets(): array
    {
        return [
           ComplianceTotal::class,
        ];
    }
}
