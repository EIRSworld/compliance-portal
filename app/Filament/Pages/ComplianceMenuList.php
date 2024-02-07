<?php

namespace App\Filament\Pages;

use App\Models\ComplianceMenu;
use App\Models\ComplianceSubMenu;
use App\Models\Country;
use App\Models\Lead;
use Filament\Actions\Action;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Http\Request;

class ComplianceMenuList extends Page implements HasTable
{
    use InteractsWithTable;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.compliance-menu-list';

    public function getHeading(): string|Htmlable
    {
        return $this->country->name;
    }

    protected static bool $shouldRegisterNavigation = false;


    public $country_id,$country;

//    protected ?string $maxContentWidth = '7xl';

    public function mount(Request $request)
    {
        $this->country_id = $request->get('country_id');
        $this->country = Country::find($this->country_id);

    }
//    public function getBreadcrumbs(): array
//    {
//        return [$this->country->name];
//    }
    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
//                ->label(function () {
//                    return ('Create ') . $this->country->name .(' Folder');
//                })
                ->label('New Folder')
                ->mountUsing(function (ComponentContainer $form) {
                    $form->fill([
                        'country_id' => $this->country_id,
                        'country_name' => $this->country->name,
                    ]);
                })
                ->form([
                    Card::make()
                        ->schema([
                            Card::make([
                                Hidden::make('country_id'),
                                TextInput::make('country_name')->label('Country')->disabled(),
                                TextInput::make('name')
                                    ->columnSpan(1)
                                    ->label('Name')
                                    ->required(),
                            ])->columns(3)
                        ])
                ])
                ->action(function (array $data, $record, $form): void {
                    $complianceMenu = new ComplianceMenu();
                    $complianceMenu->country_id = $data['country_id'];
                    $complianceMenu->name = $data['name'];
                    $complianceMenu->save();
                    Notification::make()
                        ->title('Folder Successfully Created')
                        ->success()
                        ->send();
//                        }


                })
                ->visible(function () {

                    if (auth()->user()->hasRole('compliance_manager') || auth()->user()->hasRole('super_admin')) {
                        return true;
                    }
                    return false;
                })
//                ->url(ComplianceMenuCreate::getUrl(['country_id' => $this->country_id]))
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\ComplianceMenu::query()->where('country_id', $this->country_id))
            ->columns([
                TextColumn::make('name')->label('Name')
                    ->url(fn(\App\Models\ComplianceMenu $record): string => ComplianceSubMenuList::getUrl(['compliance_menu_id' => $record->id]))

                    ->extraAttributes(function (ComplianceMenu $record) {
                        if ($record->name == 'Compliance docs with due dates') {
                            return [
                                'class' => 'custom-bg-green',
                            ];
                        } elseif ($record->name != 'Compliance docs with due dates') {
                            return [
                                'class' => 'custom-bg-red',
                            ];
                        }
                        return [];
                    }),
                TextColumn::make('updated_at')->label('Updated Date')->date('d-m-Y'),
                TextColumn::make('user.name')->label('Created By'),
            ])
            ->actions([
                \Filament\Tables\Actions\Action::make('edit')->color('warning')->button()
                    ->icon('heroicon-o-pencil')
                    ->label('Edit')
                    ->mountUsing(function (ComponentContainer $form,$record) {
                        $form->fill([
                            'country_id' => $this->country_id,
                            'country_name' => $this->country->name,
                            'name' => $record->name,
                        ]);
                    })
                    ->form([
                        Card::make()
                            ->schema([
                                Card::make([
                                    Hidden::make('country_id'),
                                    TextInput::make('country_name')->label('Country')->disabled(),
                                    TextInput::make('name')
                                        ->columnSpan(1)
                                        ->label('Name')
                                        ->required(),
                                ])->columns(3)
                            ])
                    ])

                    ->action(function (array $data, $record, $form): void {
                        $complianceMenu = ComplianceMenu::find($record->id);
                        $complianceMenu->country_id = $data['country_id'];
                        $complianceMenu->name = $data['name'];
                        $complianceMenu->save();
                        Notification::make()
                            ->title('Folder Successfully Updated')
                            ->success()
                            ->send();
//                        }


                    })
                    ->visible(function () {

                        if (auth()->user()->hasRole('compliance_manager') || auth()->user()->hasRole('super_admin')) {
                            return true;
                        }
                        return false;
                    }),

                \Filament\Tables\Actions\Action::make('delete')->color('danger')
                    ->icon('heroicon-o-trash')
                    ->label('Delete')
                    ->button()
                    ->requiresConfirmation()
                    ->action(function (array $data, $record, $form): void {
                        $complianceMenu = ComplianceMenu::find($record->id)->delete();
                        Notification::make()
                            ->title('Deleted')
                            ->success()
                            ->send();
//                        }


                    })
                    ->visible(function () {

                        if (auth()->user()->hasRole('compliance_manager') || auth()->user()->hasRole('super_admin')) {
                            return true;
                        }
                        return false;
                    }),
            ], position: ActionsPosition::AfterColumns);
    }
}
