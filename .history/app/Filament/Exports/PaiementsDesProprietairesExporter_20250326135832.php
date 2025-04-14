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
                ->formatStateUsing(function ($state) {
                    return is_numeric($state) ? number_format($state, 2) . ' DZD' : '0.00 DZD';
                }),
            
            ExportColumn::make('datepaie')
                ->label('Date de paiement')
                ->formatStateUsing(function ($state) {
                    try {
                        return $state ? \Carbon\Carbon::parse($state)->format('d-m-Y') : '';
                    } catch (\Exception $e) {
                        return '';
                    }
                }),
            
            ExportColumn::make('codebanque')
                ->label('Banque')
                ->formatStateUsing(function ($state, PaiementsDesProprietaires $record) {
                    try {
                        return $record->banque 
                            ? "{$record->codebanque} ({$record->banque->nomdebanque})"
                            : $record->codebanque;
                    } catch (\Exception $e) {
                        return $record->codebanque;
                    }
                }),
            
            ExportColumn::make('code_proprietaire')
                ->label('Propriétaire')
                ->formatStateUsing(function ($state, PaiementsDesProprietaires $record) {
                    try {
                        return $record->proprietaire
                            ? "{$record->code_proprietaire} ({$record->proprietaire->nom_proprietaire})"
                            : $record->code_proprietaire;
                    } catch (\Exception $e) {
                        return $record->code_proprietaire;
                    }
                }),
        ];
    }

    public static function prepareQuery(Builder $query): Builder
    {
        return $query->with([
            'banque' => function($query) {
                $query->select('codebanque', 'nomdebanque', 'adressedebanque');
            },
            'proprietaire' => function($query) {
                $query->select('code_proprietaire', 'nom_proprietaire', 'prenom_proprietaire');
            }
        ]);
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export des paiements des propriétaires terminé avec ' . number_format($export->successful_rows) . ' ' . str('ligne')->plural($export->successful_rows) . ' exportées.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('ligne')->plural($failedRowsCount) . ' ont échoué.';
        }

        return $body;
    }
}