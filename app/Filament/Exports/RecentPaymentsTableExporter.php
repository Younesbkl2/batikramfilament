<?php

namespace App\Filament\Exports;

use App\Models\Paiement;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class RecentPaymentsTableExporter extends Exporter
{
    protected static ?string $model = Paiement::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('datepaie')
                ->label('Date de Paiement')
                ->formatStateUsing(fn ($state): ?string => $state?->format('d/m/Y')),
            
            ExportColumn::make('codeclient')
                ->label('Client')
                ->formatStateUsing(function (mixed $state, Paiement $paiement) {
                    $client = $paiement->client;
                    return $client 
                        ? "{$client->nomclient} {$client->prenomclient} ({$paiement->codeclient})"
                        : $paiement->codeclient;
                }),
            
            ExportColumn::make('montantpaie')
                ->label('Montant')
                ->formatStateUsing(fn ($state): ?string => number_format($state, 2).' DZD'),
            
            ExportColumn::make('modepaie')
                ->label('Mode de Paiement'),
            
            ExportColumn::make('codebanque')
                ->label('Banque')
                ->formatStateUsing(function (mixed $state, Paiement $paiement) {
                    $banque = $paiement->banque;
                    return $banque
                        ? "{$banque->nomdebanque} ({$banque->adressedebanque})"
                        : $paiement->codebanque;
                }),
        ];
    }

    public static function prepareQuery(Builder $query): Builder
    {
        return $query->with(['client', 'banque']);
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export des paiements récents terminé avec '.number_format($export->successful_rows).' lignes exportées.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' lignes ont échoué.';
        }

        return $body;
    }
}