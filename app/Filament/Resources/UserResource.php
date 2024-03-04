<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\Country;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Masters';
    protected static ?int $navigationSort = 3;



    public static function canCreate(): bool
    {
        if (auth()->user()->can('Create User')) {
            return true;
        }
        return false;

    }

    public static function canEdit(Model $record): bool
    {
        if (auth()->user()->can('Edit User')) {
            return true;
        }
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        if (auth()->user()->can('Delete User')) {
            return true;
        }
        return false;
    }

//    public static function shouldRegisterNavigation(): bool
//    {
////        if (auth()->user()->can('View User')) {
////            return true;
////        }
//        return true;
//    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make([
                    Forms\Components\TextInput::make('name')
                        ->columnSpan(1)
                        ->required(),

                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->columnSpan(1)
                        ->required(),
                    Forms\Components\Select::make('roles')
                        ->searchable()->preload()
                        ->columnSpanFull()->reactive()
                        ->relationship('roles', 'name',
                            modifyQueryUsing: function (Builder $query) {
                                $query->get();
                            })
                        ->columnSpan(1),
                    Forms\Components\Select::make('country_id')->label('Country')->multiple()

                        ->options(Country::pluck('name', 'id')->toArray())
                        ->default(function (callable $get){
                            $roleId = $get('roles');
                            $role = Role::find($roleId);
                            if ($role){

                                if ($role->name === 'Management' || $role->name === 'Super Admin' || $role->name === 'Business Head' || $role->name === 'Compliance Head') {
                                    Country::pluck('id')->toArray();
                                }
                            }
})
                        ->searchable()
                        ->reactive()->required()
                        ->placeholder('Select'),

                    Forms\Components\Select::make('finance_manager_id')->label('Finance Manager')
//                        ->options(function (callable $get) {
//                            $countryIds = $get('country_id');
//                          return User::role('Compliance Finance Manager')->whereJsonContains('country_id',$countryIds)->pluck('name','id')->toArray();
//
//                        })
                        ->options(function (callable $get) {
                            $countryIds = $get('country_id');
                            // Ensure we're dealing with an array of country IDs.
                            if (!is_array($countryIds)) {
                                $countryIds = [$countryIds];
                            }

                            $query = User::role('Compliance Finance Manager');
                            // Apply whereJsonContains for each country ID individually
                            foreach ($countryIds as $countryId) {
//                                dd($query->orWhereJsonContains('country_id', (string)$countryId));
                                // Note: Depending on your exact database and Laravel version,
                                // you might need to adjust this to ensure compatibility.
                                $query->orWhereJsonContains('country_id', (string)$countryId);
                            }
//dd($query);
                            return $query->pluck('name', 'id')->toArray();
                        })
                        ->searchable()
                        ->reactive()
                        ->placeholder('Select')
                        ->visible(function (callable $get) {
                            $roleIds = $get('roles');
                            if (!is_array($roleIds)) {
                                $roleIds = [$roleIds];
                            }
                            $roles = Role::findMany($roleIds);

                            foreach ($roles as $role) {
                                if (in_array($role->name, ['Compliance Finance Officer', 'Compliance Principle Officer'])) {
                                    return true;
                                }
                            }
                            return false;
                        }),
//                        ->visible(function (callable $get) {
//                            $roleId = $get('roles');
////                            dd($roleId);
//                            $role = Role::findMany($roleId);
//                            if ($role){
//
//                                if ($role->name === 'Compliance Finance Officer' || $role->name === 'Compliance Principle Officer') {
//                                    return true;
//                                }
//                            }
//                            return false;
//                        }),
                    Forms\Components\Select::make('principal_manager_id')->label('Principal Manager')
                        ->options(function (callable $get) {
                            $country = $get('country_id');

                            User::role('Compliance Principle Manager')->whereIn('country_id',$country)->pluck('id','name')->toArray();

                        })
                        ->searchable()
                        ->reactive()
                        ->placeholder('Select')
                        ->visible(function (callable $get) {
                            $roleIds = $get('roles');
                            if (!is_array($roleIds)) {
                                $roleIds = [$roleIds];
                            }
                            $roles = Role::findMany($roleIds);

                            foreach ($roles as $role) {
                                if (in_array($role->name, ['Compliance Finance Officer', 'Compliance Principle Officer'])) {
                                    return true;
                                }
                            }
                            return false;
                        }),
                    Forms\Components\TextInput::make('password')
                        ->hiddenOn('edit')
                        ->dehydrateStateUsing(fn($state) => !empty($state) ? Hash::make($state) : "")
                        ->label('Password')
                        ->password(),
                    Forms\Components\TextInput::make('confirm_password')
                        ->password()
                        ->hiddenOn('edit')
                        ->label('Confirm Password')
                        ->same('password'),
                ])->columns(3)


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),

                TextColumn::make('roles.name')->badge()->color('success')->label('Role'),
                TextColumn::make('country_id')->label('Country')
                    ->getStateUsing(function (User $record) {
                        if ($record->country_id) {
                            return Country::whereIn('id', $record->country_id)->pluck('name')->toArray();
                        }
                        return [];
                    })->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
//            'create' => Pages\CreateUser::route('/create'),
//            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
