<?php

namespace App\Filament\Widgets;

use App\Models\ComplianceSubMenu;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Query\Builder;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ComplianceTotal extends BaseWidget
{

    protected int | string | array $columnSpan = 2;
    protected static ?string $heading = 'Compliance Document Report';

//    public function mount()
//    {
//        $complianceSubMenu =ComplianceSubMenu::
//        $stocksMedia = Media::whereModelType('App\Models\StockEntry')->whereCollectionName('stock_documents')
//            ->groupBy('model_id')->get()->pluck('model_id')->toArray();
//    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function (Builder $query) {

            $baseQuery = ComplianceSubMenu::whereRelation('complianceMenu', 'name', 'Compliance docs with due dates');
            if (auth()->user()->hasRole('country_head')) {

                return $baseQuery->where('country_id', auth()->user()->country_id);
            }

            return $baseQuery;
        })
            ->columns([

                Tables\Columns\TextColumn::make('country.name')->label('Country Name'),
                Tables\Columns\TextColumn::make('complianceMenu.name')->label('Folder Name'),
                Tables\Columns\TextColumn::make('name')->label('Name'),
                Tables\Columns\TextColumn::make('expired_date')->label('Expired Date')->date('d-m-Y'),
                Tables\Columns\IconColumn::make('media')->label('Uploaded Status')

                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark')
                    ->boolean(function (ComplianceSubMenu $record) {

                        $mediaFile = Media::whereModelType('App\Models\ComplianceSubMenu')->whereModelId($record->id)->whereCollectionName('compliant_attachments')->get();
//                    dd($media);
                        if ($mediaFile) {
                            return 1;
                        } else {
                            return 0;
                        }
                    }),
            ]);
    }
}
