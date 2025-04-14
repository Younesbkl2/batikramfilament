<?php

namespace App\Filament\Widgets;

use App\Models\Paiement;
use Filament\Widgets\TableWidget;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\ExportBulkAction;
use App\Filament\Exports\RecentPaymentsTableExporter;
use Filament\Tables\Filters\Tabs;
use Carbon\Carbon;
use Filament\Tables\Filters\Tabs;

class RecentPaymentsTable extends TableWidget
{
    protected static ?int $sort = 9;
    protected static bool $isLazy = false;
    protected static ?string $heading = 'Activité Récente des Paiements';
    protected int | string | array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        return Paiement::query()
            ->with(['client', 'banque'])
            ->latest('datepaie');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('datepaie')
                ->date('d/m/Y')
                ->label('Date'),
                
            TextColumn::make('client.nomclient')
                ->label('Client')
                ->formatStateUsing(fn($record) => "{$record->client->nomclient} {$record->client->prenomclient} ({$record->codeclient})")
                ->searchable(),
                
            TextColumn::make('montantpaie')
                ->money('DZD')
                ->label('Montant'),
                
            TextColumn::make('modepaie')
                ->label('Mode de Paiement')
                ->searchable(),
                
            TextColumn::make('banque.nomdebanque')
                ->label('Banque')
                ->searchable()
                ->formatStateUsing(fn($record) => "{$record->banque->nomdebanque} ({$record->banque->adressedebanque})"),
        ];
    }

    protected function getTableBulkActions(): array
    {
        return [
            ExportBulkAction::make()
                ->label('Exporter les Paiements récents sélectionnés')
                ->exporter(RecentPaymentsTableExporter::class)
        ];
    }

    protected function getTableHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->label('Exporter tous les Paiements récents')
                ->exporter(RecentPaymentsTableExporter::class)
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            Tabs::make('date_range')
                ->tabs([
                    'this_week' => Tabs\Tab::make('Cette Semaine')
                        ->modifyQuery(fn (Builder $query) => 
                            $query->whereBetween('datepaie', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                        ),

                    'this_month' => Tabs\Tab::make('Ce Mois')
                        ->modifyQuery(fn (Builder $query) => 
                            $query->whereBetween('datepaie', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                        ),

                    'this_year' => Tabs\Tab::make('Cette Année')
                        ->modifyQuery(fn (Builder $query) => 
                            $query->whereBetween('datepaie', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])
                        ),
                ])
                ->default('this_week'), // Default to "This Week"
        ];
    }
}
