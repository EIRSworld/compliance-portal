<?php

namespace App\Filament\Pages;

use App\Models\ComplianceMenu;
use App\Models\ComplianceSubMenu;
use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Http\Request;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ComplianceSubMenuList extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.compliance-sub-menu-list';
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = 'Compliance';
    public $compliance_menu_id,$compliance_menu;

    protected ?string $maxContentWidth = '7xl';

    public function mount(Request $request)
    {
        $this->compliance_menu_id = $request->get('compliance_menu_id');
        $this->compliance_menu = ComplianceMenu::find($this->compliance_menu_id);
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('create')
//                ->label(function () {
//                    return ('Create ') . $this->compliance_menu->name .(' Sub Folder');
//                })
                ->label('Create Sub Folder')

                ->url(ComplianceSubMenuCreate::getUrl(['compliance_menu_id' => $this->compliance_menu_id]))
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(\App\Models\ComplianceSubMenu::query()->where('compliance_menu_id', $this->compliance_menu_id))
            ->columns([
                TextColumn::make('name')->label('Name')
                    ->url(fn(\App\Models\ComplianceSubMenu $record): string => CompliantView::getUrl(['compliant_sub_menu_id' => $record->id])),
                TextColumn::make('updated_at')->label('Updated Date')->date('d-m-Y'),
                TextColumn::make('expired_date')->label('Expired Date')->date('d-m-Y'),
                TextColumn::make('user.name')->label('Updated By'),
            ])
            ->actions([
                Action::make('upload_file')->color('success')
                    ->icon('heroicon-o-clipboard-document')
                    ->label('Upload File')
                    ->mountUsing(function (ComponentContainer $form) {
                        $form->fill([
                            'media' => $form->getRecord()->getMedia('compliant_attachments'),
                        ]);
                    })
                    ->form([
                        Card::make()
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('media')
                                    ->label('Upload File')
                                    ->model()
                                    ->collection('compliant_attachments')
                                    ->preserveFilenames()
                                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                        return (string)str($file->getClientOriginalName())->prepend('compliant_attachments-');
                                    })
                                    ->acceptedFileTypes(['application/pdf'])
                                    ->appendFiles()
                                    ->downloadable()
                                    ->openable()
                                    ->multiple(),
                            ])
                    ])
                    ->action(function (array $data, $record, $form): void {
//                        if (isset($data['media']) && !empty($data['media'])){
//                            $complianceSubMenu = \App\Models\ComplianceSubMenu::find($record->id);
//                            $complianceSubMenu->is_uploaded = 1;
//                            $complianceSubMenu->save();
                            Notification::make()
                                ->title('File Update Successfully')
                                ->success()
                                ->send();
//                        }


                    })
//                    ->after()
                    ->modalWidth('md'),
//                Action::make('uploaded_files')
////                    ->action(function (Pages\ListInvoicedOrders $livewire,Order $order, array $data){
////
////                    })
////                    ->modalContent(function (ComplianceSubMenu $record) {
//                     ->   url(fn(\App\Models\ComplianceSubMenu $record): string => CompliantView::getUrl(['compliant_sub_menu_id' => $record->id]))->slideOver()
////                    })
//                    ->icon('heroicon-o-document-text')
//                    ->slideOver(),

                Action::make('update_date')->color('warning')
                    ->icon('heroicon-o-calendar')
                    ->form([
                        Card::make()
                            ->schema([
                                DatePicker::make('expired_date')
                                    ->label('Update Date')
                                    ->required()
                                    ->suffixIcon('heroicon-o-calendar')
                                    ->closeOnDateSelection()
                                    ->native(false),
                            ])
                    ])
                    ->action(function (array $data, $record, $form): void {
                        $data = $form->getState();
                        $complianceSubMenu = \App\Models\ComplianceSubMenu::find($record->id);
                        $complianceSubMenu->update($data);
                        Notification::make()
                            ->title('Update Successfully')
                            ->success()
                            ->send();
                    })
                    ->modalWidth('sm')
//                    ->visible(function () {
//                        if (auth()->user()->hasRole('country_head') || auth()->user()->hasRole('super_admin') ) {
//                            return true;
//                        }
//                        return false;
//                    }),


            ]);
    }
}
