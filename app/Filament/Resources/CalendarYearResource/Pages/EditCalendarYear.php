<?php

namespace App\Filament\Resources\CalendarYearResource\Pages;

use App\Filament\Resources\CalendarYearResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCalendarYear extends EditRecord
{
    protected static string $resource = CalendarYearResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
