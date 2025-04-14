<?php

namespace App\Filament\Exports;

use App\Models\PaiementsDesProprietaires;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class PaiementsDesProprietairesExporter extends Exporter
{
    protected static ?string $model = PaiementsDesProprietaires::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('codepaie')
                ->label('Code Paiement'),
            
            ExportColumn::make('codachat')
                ->label('Achat ID'),
            
            ExportColumn::make('codeclient')
                ->label('Code Client'),
            
            ExportColumn::make('nomclient')
                ->label('Nom Client'),
            
            ExportColumn::make('prenomclient')
                ->label('Prénom Client'),
            
            ExportColumn::make('modepaie')
                ->label('Mode de paiement'),
            
            ExportColumn::make('montantpaie')
                ->label('Montant payé')
                ->formatStateUsing(fn ($state) => number_format($state, 2) . ' DZD'),
            
            ExportColumn::make('datepaie')
                ->label('Date de paiement')
                ->formatStateUsing(fn ($state) => $state?->format('d-m-Y')),
            
            ExportColumn::make('banque')
                ->label('Banque')
                ->formatStateUsing(function (mixed $state, PaiementsDesProprietaires $record) {
                    return $record->banque 
                        ? "{$record->codebanque} ({$record->banque->nomdebanque} {$record->banque->adressedebanque})"
                        : $record->codebanque;
                }),
            
            ExportColumn::make('proprietaire')
                ->label('Propriétaire')
                ->formatStateUsing(function (mixed $state, PaiementsDesProprietaires $record) {
                    return $record->proprietaire
                        ? "{$record->code_proprietaire} ({$record->proprietaire->nom_proprietaire} {$record->proprietaire->prenom_proprietaire})"
                        : $record->code_proprietaire;
                }),
        ];
    }

    public static function prepareQuery(Builder $query): Builder
    {
        return $query->with(['banque', 'proprietaire']);
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your paiements des proprietaires export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}