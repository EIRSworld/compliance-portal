<?php

namespace App\Filament\Pages;

use App\Models\Country;
use App\Models\User;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder;

class ComplianceList extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.compliance-list';

    protected static ?string $title = 'Documents';


    public function table(Table $table): Table
    {

        return $table
            ->query(function (Builder $query) {

                $baseQuery = Country::query();
                if (auth()->user()->hasRole('country_head')) {

                    return $baseQuery->whereIn('id', auth()->user()->country_id);
                }

                return $baseQuery;
            })
            ->columns([
                TextColumn::make('name')->label('Name')->searchable()
                    ->url(fn(Country $record): string => ComplianceMenuList::getUrl(['country_id' => $record->id])),
                TextColumn::make('updated_at')->label('Updated Date')->date('d-m-Y'),
                TextColumn::make('user.name')->label('Updated By'),
            ]);
    }
}
