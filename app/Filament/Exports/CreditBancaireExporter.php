<?php

namespace App\Filament\Exports;

use App\Models\CreditBancaire;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class CreditBancaireExporter extends Exporter
{
    protected static ?string $model = CreditBancaire::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')->label('ID'),
            
            ExportColumn::make('codeclient')
                ->formatStateUsing(function (mixed $state, CreditBancaire $credit) {
                    $client = $credit->client;
                    return $client 
                        ? "{$credit->codeclient} ({$client->nomclient} {$client->prenomclient})"
                        : $credit->codeclient;
                }),
            
            ExportColumn::make('codebanque')
                ->formatStateUsing(function (mixed $state, CreditBancaire $credit) {
                    $banque = $credit->banque;
                    return $banque
                        ? "{$credit->codebanque} ({$banque->nomdebanque} {$banque->adressedebanque})"
                        : $credit->codebanque;
                }),
            
            ExportColumn::make('depotdossier')
                ->formatStateUsing(fn ($state) => $state ? 'Oui' : 'Non'),
            
            ExportColumn::make('comite')
                ->formatStateUsing(fn ($state) => $state ? 'Oui' : 'Non'),
            
            ExportColumn::make('paiementfrais')
                ->formatStateUsing(fn ($state) => $state ? 'Oui' : 'Non'),
            
            ExportColumn::make('signatureconvenrg')
                ->formatStateUsing(fn ($state) => $state ? 'Oui' : 'Non'),
            
            ExportColumn::make('dossiertransfnotaire')
                ->formatStateUsing(fn ($state) => $state ? 'Oui' : 'Non'),
            
            ExportColumn::make('signvspclientgerant')
                ->formatStateUsing(fn ($state) => $state ? 'Oui' : 'Non'),
            
            ExportColumn::make('recuperationcheque')
                ->formatStateUsing(fn ($state) => $state ? 'Oui' : 'Non'),
            
            ExportColumn::make('enrgvsp')
                ->formatStateUsing(fn ($state) => $state ? 'Oui' : 'Non'),
            
            ExportColumn::make('publicationvsp')
                ->formatStateUsing(fn ($state) => $state ? 'Oui' : 'Non'),
            
            ExportColumn::make('paiementtranches')
                ->formatStateUsing(fn ($state) => $state ? 'Oui' : 'Non'),
        ];
    }

    public static function prepareQuery(Builder $query): Builder
    {
        return $query->with(['client', 'banque']);
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your credit bancaire export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}