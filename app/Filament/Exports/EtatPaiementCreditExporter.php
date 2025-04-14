<?php

namespace App\Filament\Exports;

use App\Models\EtatPaiementCredit;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class EtatPaiementCreditExporter extends Exporter
{
    protected static ?string $model = EtatPaiementCredit::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('codachat')
                ->label('Achat ID'),
            
            ExportColumn::make('codeclient')
                ->label('Code Client'),
            
            ExportColumn::make('nomclient')
                ->label('Nom Client')
                ->formatStateUsing(fn ($state, EtatPaiementCredit $record) => $record->client->nomclient ?? ''),
            
            ExportColumn::make('prenomclient')
                ->label('Prénom Client')
                ->formatStateUsing(fn ($state, EtatPaiementCredit $record) => $record->client->prenomclient ?? ''),
            
            ExportColumn::make('Numdetel')
                ->label('Numéro de téléphone 1'),
            
            ExportColumn::make('NUM_TEL')
                ->label('Numéro de téléphone 2'),
            
            ExportColumn::make('numappartement')
                ->label('Appartement'),
            
            ExportColumn::make('projet')
                ->label('Projet')
                ->formatStateUsing(fn ($state, EtatPaiementCredit $record) => $record->projet 
                    ? "{$record->projet->Libelleprj} ({$record->projet->adresseprj})"
                    : ''),
            
            ExportColumn::make('proprietaire')
                ->label('Propriétaire')
                ->formatStateUsing(fn ($state, EtatPaiementCredit $record) => $record->proprietaire 
                    ? "{$record->proprietaire->nom_proprietaire} {$record->proprietaire->prenom_proprietaire}"
                    : ''),
            
            ExportColumn::make('Observations')
                ->label('Observation'),
        ];
    }

    public static function prepareQuery(Builder $query): Builder
    {
        return $query->with(['client', 'projet', 'proprietaire']);
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your etat paiement credit export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}