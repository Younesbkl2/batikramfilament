<?php

namespace App\Filament\Exports;

use App\Models\Paiement;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class PaiementExporter extends Exporter
{
    protected static ?string $model = Paiement::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('codepaie')
                ->label('Code Paiement')
                ->sortable(),
            
            ExportColumn::make('modepaie')
                ->label('Mode de Paiement')
                ->sortable(),
            
            ExportColumn::make('banque')
                ->label('Banque')
                ->formatStateUsing(function (mixed $state, Paiement $paiement) {
                    $banque = $paiement->banque;
                    return $banque 
                        ? "{$paiement->codebanque} ({$banque->nomdebanque} {$banque->adressedebanque})"
                        : $paiement->codebanque;
                }),
            
            ExportColumn::make('montantpaie')
                ->label('Montant')
                ->formatStateUsing(fn ($state) => number_format($state, 2) . ' DZD'),
            
            ExportColumn::make('datepaie')
                ->label('Date de Paiement')
                ->formatStateUsing(fn ($state) => $state?->format('d-m-Y')),
            
            ExportColumn::make('client')
                ->label('Client')
                ->formatStateUsing(function (mixed $state, Paiement $paiement) {
                    $client = $paiement->client;
                    return $client 
                        ? "{$paiement->codeclient} ({$client->nomclient} {$client->prenomclient})"
                        : $paiement->codeclient;
                }),
            
            ExportColumn::make('codachat')
                ->label('Code Achat'),
        ];
    }

    public static function prepareQuery(Builder $query): Builder
    {
        return $query->with(['banque', 'client']);
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your paiement export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}