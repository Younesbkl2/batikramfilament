<?php

namespace App\Filament\Exports;

use App\Models\Achat;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class AchatExporter extends Exporter
{
    protected static ?string $model = Achat::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('codachat'),
            ExportColumn::make('codeclient')
                ->formatStateUsing(function (Achat $achat) {
                    $client = $achat->client;
                    return $client 
                        ? "{$achat->codeclient} ({$client->nomclient} {$client->prenomclient})"
                        : $achat->codeclient;
                }),
            ExportColumn::make('codeprod')
                ->formatStateUsing(function (Achat $achat) {
                    $produit = $achat->produit;
                    return $produit
                        ? "{$achat->codeprod} ({$produit->Typeproduit})"
                        : $achat->codeprod;
                }),
            ExportColumn::make('codebanque')
                ->formatStateUsing(function (Achat $achat) {
                    $banque = $achat->banque;
                    return $banque
                        ? "{$achat->codebanque} ({$banque->nomdebanque} {$banque->adressedebanque})"
                        : $achat->codebanque;
                }),
            ExportColumn::make('numappartement'),
            ExportColumn::make('numparking'),
            ExportColumn::make('Numlocal'),
            ExportColumn::make('Observations'),
            ExportColumn::make('ID_ATTESTATION')->label('ID_ATTESTATION'),
        ];
    }

    // Add this method to eager load relationships
    public static function prepareQuery(Builder $query): Builder
    {
        return $query->with(['client', 'produit', 'banque', 'appartement', 'parking', 'local', 'attestation']);
    }

    // Keep existing notification method
    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your achat export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}