<?php

namespace App\Filament\Pages;

use App\Models\CalendarYear;
use App\Models\ComplianceMenu;
use App\Models\CompliancePrimarySubMenu;
use App\Models\ComplianceSubMenu;
use App\Models\Country;
use App\Models\Document;
use App\Models\UploadDocument;
use App\Models\User;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Query\Builder;

class ComplianceList extends Page implements HasTable
{
    use InteractsWithTable;



    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.compliance-list';

    protected static ?string $title = 'Documents';

    protected static ?int $navigationSort = 0;

    public static function canAccess(): bool
    {
        return auth()->user()->can('view Document');
    }

    public function table(Table $table): Table
    {

        return $table
            ->query(function (Builder $query) {

                $query = Document::query();
//                if (auth()->user()->hasRole('country_head')) {
//                    return $query->whereIn('country_id', auth()->user()->country_id);
//                }

                return $query;
            })
            ->columns([
                TextColumn::make('name')->label('Name')->searchable()
                    ->url(fn(Document $record): string => ComplianceMenuList::getUrl([
//                        dd($record->calendar_year_id);
                        'document_id' => $record->id,
                        'calendar_year_id' => $record->calendar_year_id,
                        ])),
                TextColumn::make('updated_at')->label('Updated Date')->date('d-m-Y'),
                TextColumn::make('user.name')->label('Created By')
                ->getStateUsing(function(Document $record){
                    $user = User::find($record->created_by);
                    return $user->name;
                }),
            ])
            ->filters([
                SelectFilter::make('calendar_year_id')->searchable()
                    ->options(function () {
                        return CalendarYear::pluck('name', 'id')->toArray();
                    })

                    ->default(function(){
                        $currentYear = Carbon::now()->year;
                        return CalendarYear::where('name', $currentYear)->value('id');
                    })
                    ->placeholder('Select the Year')
                    ->label('Year'),
            ], FiltersLayout::AboveContent)


//            ->actions([
//                \Filament\Tables\Actions\Action::make('delete')->color('danger')
//                    ->icon('heroicon-o-trash')
//                    ->label('Delete')
//                    ->button()
//                    ->requiresConfirmation()
//                    ->action(function (array $data, $record, $form): void {
////                        dd($record);
//                        $document = Document::find($record->id)->delete();
//                        $complianceMenu = ComplianceMenu::where('document_id',$record->id)->delete();
//                        $complianceSubMenu = ComplianceSubMenu::where('document_id', $record->id)->delete();
//                        $compliancePrimarySubMenu = CompliancePrimarySubMenu::where('document_id', $record->id)->delete();
//                        $complianceUploadDocument = UploadDocument::where('document_id', $record->id)->delete();
//                        Notification::make()
//                            ->title('Deleted Successfully')
//                            ->success()
//                            ->send();
////                        }
//
//
//                    })
//                    ->visible(function () {
//
//                        if (auth()->user()->hasRole('Super Admin')) {
//                            return true;
//                        }
//                        return false;
//                    }),
//                ])
            ;
    }
}
