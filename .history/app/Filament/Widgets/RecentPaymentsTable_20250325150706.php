<?php



namespace App\Filament\Widgets;

use App\Models\Paiement;
use Filament\Widgets\TableWidget;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

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
}
