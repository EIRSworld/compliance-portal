<?php

namespace App\Filament\Pages;

use App\Models\Country;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Http\Request;

class ComplianceMenuList extends Page implements HasTable
{
    use InteractsWithTable;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.compliance-menu-list';

    protected static bool $shouldRegisterNavigation = false;


    public $country_id,$country;

    protected ?string $maxContentWidth = '7xl';

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
                ->label('Create Folder')

                ->url(ComplianceMenuCreate::getUrl(['country_id' => $this->country_id]))
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\ComplianceMenu::query()->where('country_id', $this->country_id))
            ->columns([
                TextColumn::make('name')->label('Name')
                    ->url(fn(\App\Models\ComplianceMenu $record): string => ComplianceSubMenuList::getUrl(['compliance_menu_id' => $record->id])),
                TextColumn::make('updated_at')->label('Updated Date')->date('d-m-Y'),
                TextColumn::make('user.name')->label('Updated By'),
            ]);
    }
}
