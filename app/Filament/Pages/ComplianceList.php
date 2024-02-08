<?php

namespace App\Filament\Pages;

use App\Models\CalendarYear;
use App\Models\Country;
use App\Models\Document;
use App\Models\User;
use Carbon\Carbon;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
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

                $query = Document::query();
                if (auth()->user()->hasRole('country_head')) {
                    return $query->whereIn('country_id', auth()->user()->country_id);
                }

                return $query;
            })
            ->columns([
                TextColumn::make('name')->label('Name')->searchable()
                    ->url(fn(Document $record): string => ComplianceMenuList::getUrl([
//                        dd($record->calendar_year_id);
                        'document_id' => $record->id,
                        'calendar_year_id' => $record->calendar_year_id,
                        ])),
                TextColumn::make('updated_at')->label('Updated Date')->date('d-m-Y'),
                TextColumn::make('user.name')->label('Created By')
                ->getStateUsing(function(Document $record){
                    $user = User::find($record->created_by);
                    return $user->name;
                }),
            ])
            ->filters([
                SelectFilter::make('calendar_year_id')->searchable()
                    ->options(function () {
                        return CalendarYear::pluck('name', 'id')->toArray();
                    })

                    ->default(function(){
                        $currentYear = Carbon::now()->year;
                        return CalendarYear::where('name', $currentYear)->value('id');
                    })
                    ->placeholder('Select the Country')
                    ->label('Year'),
            ], FiltersLayout::AboveContent)
            ;
    }
}
