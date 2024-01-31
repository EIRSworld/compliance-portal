<?php

namespace App\Filament\Pages;

use App\Models\ComplianceMenu;
use App\Models\Country;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ComplianceMenuCreate extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.compliance-menu-create';
    protected static bool $shouldRegisterNavigation = false;
    public $country_id, $country, $country_name, $name;

    public function mount(Request $request)
    {
        $this->country_id = $request->get('country_id');
        $this->country = Country::find($this->country_id);
        $this->country_name = $this->country->name;
    }

    protected function getFormModel(): Model|string|null
    {
        return ComplianceMenu::class;
    }

    protected function getFormSchema(): array
    {
        return [

            Card::make([
                TextInput::make('country_name')->label('Country')->disabled(),
                TextInput::make('name')
                    ->columnSpan(1)
                    ->label('Name')
                    ->required(),
            ])->columns(3)

        ];
    }

    public function submit()
    {
        try {
            DB::beginTransaction();

            $complianceMenu = new ComplianceMenu();
            $complianceMenu->country_id = $this->country_id;
            $complianceMenu->name = $this->name;
            $complianceMenu->save();

            DB::commit();

            Notification::make()
                ->title('Folder Successfully Created')
                ->success()
                ->send();
//

            return redirect(ComplianceMenuList::getUrl(['country_id' => $this->country_id]));
        } catch (\Exception $exception) {
            Log::emergency("Exception while adding Folder " . $exception->getMessage() . " on line " . $exception->getLine());

            Notification::make()
                ->title($exception->getMessage())
                ->danger()
                ->send();
        }
    }
}
