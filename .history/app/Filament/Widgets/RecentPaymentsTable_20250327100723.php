<?php

namespace App\Filament\Widgets;

use App\Models\Paiement;
use Filament\Widgets\TableWidget;
use Filament\Resources\Components\Tab;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\ExportAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ExportBulkAction;
use App\Filament\Exports\RecentPaymentsTableExporter; // Add this import

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
            ->latest('datepaie')
            ->limit(10);
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

    // Add header actions with export
    protected function getTableHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->label('Exporter tous les Paiements récents')
                ->exporter(RecentPaymentsTableExporter::class)
        ];
    }

    public function getTabs(): array
    {
        return [
            'This Week' => Tab::make(),
            'This Month' => Tab::make(),
            'This Year' => Tab::make(),
        ];
    }

}