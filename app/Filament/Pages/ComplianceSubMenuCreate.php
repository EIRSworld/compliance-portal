<?php

namespace App\Filament\Pages;

use App\Models\ComplianceMenu;
use App\Models\ComplianceSubMenu;
use App\Models\Country;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ComplianceSubMenuCreate extends Page implements HasForms
{
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.compliance-sub-menu-create';
    protected static bool $shouldRegisterNavigation = false;

    public $compliance_menu_id,$compliance_menu,$compliance_name,$country,$country_name,$name,$expired_date;

    public function mount(Request $request)
    {
        $this->compliance_menu_id = $request->get('compliance_menu_id');

        $this->compliance_menu = ComplianceMenu::find($this->compliance_menu_id);
        $this->compliance_name = $this->compliance_menu->name;

        $this->country = Country::find($this->compliance_menu->country_id);
        $this->country_name = $this->country->name;
    }

    protected function getFormModel(): Model|string|null
    {
        return ComplianceSubMenu::class;
    }

    protected function getFormSchema(): array
    {
        return [

            Card::make([
                TextInput::make('country_name')->label('Country')->disabled(),
                TextInput::make('compliance_name')->label('Country')->disabled(),
                TextInput::make('name')
                    ->columnSpan(1)
                    ->label('Name')
                    ->required(),
//                DatePicker::make('expired_date')
//                    ->label('Update Date')
//                    ->required()
//                    ->suffixIcon('heroicon-o-calendar')
//                    ->closeOnDateSelection()
//                    ->native(false),
            ])->columns(3)

        ];
    }

    public function submit()
    {
        try {
            DB::beginTransaction();

            $complianceMenu = new ComplianceSubMenu();
            $complianceMenu->country_id = $this->country->id;
            $complianceMenu->compliance_menu_id = $this->compliance_menu->id;
            $complianceMenu->name = $this->name;
            $complianceMenu->expired_date = $this->expired_date;
            $complianceMenu->save();

            DB::commit();

            Notification::make()
                ->title('Folder Successfully Created')
                ->success()
                ->send();
//

            return redirect(ComplianceSubMenuList::getUrl(['compliance_menu_id' => $this->compliance_menu->id]));
        } catch (\Exception $exception) {
            Log::emergency("Exception while adding Folder " . $exception->getMessage() . " on line " . $exception->getLine());

            Notification::make()
                ->title($exception->getMessage())
                ->danger()
                ->send();
        }
    }
}
