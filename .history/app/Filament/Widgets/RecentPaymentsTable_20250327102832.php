<?php

namespace App\Filament\Widgets;

use App\Models\Paiement;
use Filament\Widgets\TableWidget;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\ExportAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\ExportBulkAction;
use App\Filament\Exports\RecentPaymentsTableExporter;

class RecentPaymentsTable extends TableWidget
{
    protected static ?int $sort = 9;
    protected static bool $isLazy = false;
    protected static ?string $heading = 'Activité Récente des Paiements';
    protected int | string | array $columnSpan = 'full';

    public string $activeTab = 'week';

    protected function getTableQuery(): Builder
    {
        $query = Paiement::query()
            ->with(['client', 'banque'])
            ->latest('datepaie');

        match ($this->activeTab) {
            'week' => $query->whereBetween('datepaie', [now()->startOfWeek(), now()->endOfWeek()]),
            'month' => $query->whereMonth('datepaie', now()->month),
            'year' => $query->whereYear('datepaie', now()->year),
            default => $query,
        };

        return $query->limit(10);
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
            \Filament\Tables\Actions\ActionGroup::make([
                \Filament\Tables\Actions\Action::make('week')
                    ->label('Cette semaine')
                    ->color($this->activeTab === 'week' ? 'primary' : 'gray')
                    ->outlined($this->activeTab !== 'week')
                    ->action(fn() => $this->activeTab = 'week'),
                
                \Filament\Tables\Actions\Action::make('month')
                    ->label('Ce mois')
                    ->color($this->activeTab === 'month' ? 'primary' : 'gray')
                    ->outlined($this->activeTab !== 'month')
                    ->action(fn() => $this->activeTab = 'month'),
                
                \Filament\Tables\Actions\Action::make('year')
                    ->label('Cette année')
                    ->color($this->activeTab === 'year' ? 'primary' : 'gray')
                    ->outlined($this->activeTab !== 'year')
                    ->action(fn() => $this->activeTab = 'year'),
            ])
            ->label('Période')
            ->button(),
            
            ExportAction::make()
                ->label('Exporter tous les Paiements récents')
                ->exporter(RecentPaymentsTableExporter::class)
        ];
    }
}