<?php

namespace App\Filament\Exports;

use App\Models\EtatPaiement;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class EtatPaiementExporter extends Exporter
{
    protected static ?string $model = EtatPaiement::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('codachat')
                ->label('Achat ID'),
            
            ExportColumn::make('codeclient')
                ->label('Client Code')
                ->formatStateUsing(function (mixed $state, EtatPaiement $record) {
                    return "{$record->codeclient} ({$record->client->nomclient} {$record->client->prenomclient})";
                }),
            
            // Appartement Section
            ExportColumn::make('numappartement')
                ->label('Appartement'),
            
            ExportColumn::make('prix_appartement')
                ->label('Prix Appartement')
                ->formatStateUsing(fn ($state) => number_format($state, 2)),
            
            // Parking Section
            ExportColumn::make('numparking')
                ->label('Parking'),
            
            ExportColumn::make('prix_parking')
                ->label('Prix Parking')
                ->formatStateUsing(fn ($state) => number_format($state, 2)),
            
            // Local Section
            ExportColumn::make('Numlocal')
                ->label('Local'),
            
            ExportColumn::make('prix_local')
                ->label('Prix Local')
                ->formatStateUsing(fn ($state) => number_format($state, 2)),
            
            // Financial Summary
            ExportColumn::make('total_prix')
                ->label('Prix total')
                ->formatStateUsing(fn ($state) => number_format($state, 2)),
            
            ExportColumn::make('total_depense')
                ->label('Montant payé')
                ->formatStateUsing(fn ($state) => number_format($state, 2)),
            
            ExportColumn::make('reste_a_payer')
                ->label('Montant restant')
                ->formatStateUsing(fn ($state) => number_format($state, 2)),
            
            ExportColumn::make('statue_paiment')
                ->label('Statut de paiement'),
            
            // Project and Owner
            ExportColumn::make('projet')
                ->label('Projet')
                ->formatStateUsing(function (mixed $state, EtatPaiement $record) {
                    return $record->projet 
                        ? "{$record->projet->Libelleprj} {$record->projet->adresseprj}"
                        : '';
                }),
            
            ExportColumn::make('proprietaire')
                ->label('Propriétaire')
                ->formatStateUsing(function (mixed $state, EtatPaiement $record) {
                    return $record->proprietaire
                        ? "{$record->proprietaire->nom_proprietaire} {$record->proprietaire->prenom_proprietaire}"
                        : '';
                }),
        ];
    }

    public static function prepareQuery(Builder $query): Builder
    {
        return $query->with(['client', 'projet', 'proprietaire']);
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your etat paiement export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}