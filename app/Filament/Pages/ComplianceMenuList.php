<?php

namespace App\Filament\Pages;

use App\Models\CalendarYear;
use App\Models\ComplianceMenu;
use App\Models\ComplianceSubMenu;
use App\Models\Country;
use App\Models\Document;
use App\Models\Lead;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
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
        return $this->document->name;
    }

    protected static bool $shouldRegisterNavigation = false;


    public $document_id,$document,$calendar_year_id;

//    protected ?string $maxContentWidth = '7xl';

    public function mount(Request $request)
    {
        $this->calendar_year_id = $request->get('calendar_year_id');
        $this->document_id = $request->get('document_id');
//        dd($this->calendar_year_id);
        $this->document = Document::find($this->document_id);

    }
//    public function getBreadcrumbs(): array
//    {
//        return [$this->country->name];
//    }
    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->modalWidth('md')
                ->label('New Folder')
                ->mountUsing(function (ComponentContainer $form) {
                    $form->fill([
                        'document_id' => $this->document_id,
                        'calendar_year_id' => $this->calendar_year_id,
                        'document_name' => $this->document->name,

                    ]);
                })
                ->form([
                    Card::make()
                        ->schema([
                                Hidden::make('document_id'),
                                Hidden::make('calendar_year_id'),
                                TextInput::make('document_name')->label('Country')->disabled()->columnSpan(1),
                                TextInput::make('name')
                                    ->columnSpan(1)
                                    ->label('Name')
                                    ->required(),
//                                Select::make('calendar_year_id')
//                                ->options(CalendarYear::pluck('name','id'))
//                                ->label('Year')->preload()->searchable()
                            ])->columns(1)

                ])
                ->action(function (array $data, $record, $form): void {
                    $complianceMenu = new ComplianceMenu();
                    $complianceMenu->document_id = $data['document_id'];
                    $complianceMenu->calendar_year_id = $this->calendar_year_id;
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
            ->query(\App\Models\ComplianceMenu::query()->where('document_id', $this->document_id))
            ->columns([
                TextColumn::make('name')->label('Name')
                    ->url(fn(\App\Models\ComplianceMenu $record): string => ComplianceSubMenuList::getUrl([
                        'compliance_menu_id' => $record->id,
                        'calendar_year_id' => $this->calendar_year_id,
                        ]))

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
                    ->icon('heroicon-o-pencil')->modalWidth('md')
                    ->label('Edit')
                    ->mountUsing(function (ComponentContainer $form,$record) {
                        $form->fill([
                            'document_id' => $this->document_id,
                            'calendar_year_id' => $record->calendar_year_id,
                            'document_name' => $this->document->name,
                            'name' => $record->name,
                        ]);
                    })
                    ->form([
                        Card::make()
                            ->schema([
                                Card::make([
                                    Hidden::make('document_id'),
                                    Hidden::make('calendar_year_id'),
                                    TextInput::make('document_name')->label('Country')->disabled()->columnSpan(1),
                                    TextInput::make('name')
                                        ->columnSpan(1)
                                        ->label('Name')
                                        ->required(),
                                ])->columns(1)
                            ])
                    ])

                    ->action(function (array $data, $record, $form): void {
                        $complianceMenu = ComplianceMenu::find($record->id);
                        $complianceMenu->document_id = $data['document_id'];
                        $complianceMenu->calendar_year_id = $data['calendar_year_id'];
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
                        $complianceSubMenu = ComplianceSubMenu::where('compliance_menu_id',$record->id)->delete();
                        Notification::make()
                            ->title('Deleted Successfully')
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
