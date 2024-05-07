<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EntityResource\Pages;
use App\Filament\Resources\EntityResource\RelationManagers;
use App\Models\Country;
use App\Models\Entity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EntityResource extends Resource
{
    protected static ?string $model = Entity::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Masters';

    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Entities';


    public static function canAccess(): bool
    {
        return auth()->user()->can('view Entity');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make([

                    Forms\Components\TextInput::make('entity_name')
                        ->columnSpan(1)
                        ->label('Name')
                        ->required(),
                    Forms\Components\Select::make('country_id')->label('Country')
                        ->options(Country::pluck('name', 'id')->toArray())
                        ->searchable()
                        ->reactive()->required()
                        ->placeholder('Select'),
                ])->columns(1)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('country.name')->searchable(),
                Tables\Columns\TextColumn::make('entity_name')->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->modalWidth('md'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEntities::route('/'),
//            'create' => Pages\CreateEntity::route('/create'),
//            'edit' => Pages\EditEntity::route('/{record}/edit'),
        ];
    }
}
