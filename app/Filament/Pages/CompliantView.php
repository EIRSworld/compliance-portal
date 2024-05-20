<?php

namespace App\Filament\Pages;

use App\Models\CalendarYear;
use App\Models\CompliancePrimarySubMenu;
use App\Models\ComplianceSubMenu;
use Filament\Forms\ComponentContainer;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Http\Request;

class CompliantView extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.compliant-view';
    protected static bool $shouldRegisterNavigation = false;
    public $compliant_primary_sub_menu_id,$compliance_sub_menu,$calendar_year_id,$year;

    public function getHeading(): string|Htmlable
    {
        return $this->compliance_primary_sub_menu->event_name . ' view';
    }

    public function mount(Request $request)
    {
        $this->calendar_year_id = $request->get('calendar_year_id');
        $this->year = CalendarYear::find($this->calendar_year_id);
        $this->compliant_primary_sub_menu_id = $request->get('compliant_primary_sub_menu_id');
//        dd($this->compliant_primary_sub_menu_id);
        $this->compliance_primary_sub_menu = CompliancePrimarySubMenu::find($this->compliant_primary_sub_menu_id);
    }
//    public function getBreadcrumbs(): array
//    {
//        return [$this->compliance_sub_menu->country->name,$this->compliance_sub_menu->complianceMenu->name,$this->compliance_sub_menu->name];
//    }
    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\CompliancePrimarySubMenu::query()->whereId($this->compliant_primary_sub_menu_id))
            ->columns([
                ViewColumn::make('upload_file')->label('Upload Files')->view('upload_file'),
            ]);
//            ->actions([
//
//                \Filament\Tables\Actions\Action::make('delete')->color('danger')
//                    ->icon('heroicon-o-trash')
//                    ->label('Delete')
//                    ->button()
//                    ->requiresConfirmation()
//                    ->action(function (array $data, $record, $form): void {
//                        dd($record);
//                        $complianceMenu = ComplianceMenu::find($record->id)->delete();
//                        Notification::make()
//                            ->title('Deleted Successfully')
//                            ->success()
//                            ->send();
////                        }
//
//
//                    })
////                    ->visible(function () {
////
////                        if (auth()->user()->hasRole('super_admin')) {
////                            return true;
////                        }
////                        return false;
////                    }),
//            ], position: ActionsPosition::AfterColumns);
    }

}
