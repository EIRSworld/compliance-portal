<?php

namespace App\Filament\Pages;

use App\Models\ComplianceSubMenu;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Http\Request;

class CompliantView extends Page implements HasTable
{
    use InteractsWithTable;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.compliant-view';
    protected static bool $shouldRegisterNavigation = false;
    public $compliant_sub_menu_id,$compliance_sub_menu,$calendar_year_id;

    public function getHeading(): string|Htmlable
    {
        return $this->compliance_sub_menu->name . ' view';
    }

    public function mount(Request $request)
    {
        $this->calendar_year_id = $request->get('calendar_year_id');
        $this->compliant_sub_menu_id = $request->get('compliant_sub_menu_id');
        $this->compliance_sub_menu = ComplianceSubMenu::find($this->compliant_sub_menu_id);
    }
//    public function getBreadcrumbs(): array
//    {
//        return [$this->compliance_sub_menu->country->name,$this->compliance_sub_menu->complianceMenu->name,$this->compliance_sub_menu->name];
//    }
    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\ComplianceSubMenu::query()->whereId($this->compliant_sub_menu_id))
            ->columns([
                ViewColumn::make('upload_file')->label('Upload Files')->view('upload_file'),
            ]);
    }

}
