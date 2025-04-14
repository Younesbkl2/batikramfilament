<?php

namespace App\Filament\Exports;

use App\Models\PourcentagePaiement;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class PourcentagePaiementExporter extends Exporter
{
    protected static ?string $model = PourcentagePaiement::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('codachat')
                ->label('Achat ID'),
            
            ExportColumn::make('codeclient')
                ->label('Code Client')
                ->formatStateUsing(function (mixed $state, PourcentagePaiement $record) {
                    return "{$record->codeclient} ({$record->nomclient} {$record->prenomclient})";
                }),
            
            ExportColumn::make('nomclient')
                ->label('Nom Client'),
            
            ExportColumn::make('prenomclient')
                ->label('Prénom Client'),
            
            ExportColumn::make('codebanque')
                ->label('Banque')
                ->formatStateUsing(function (mixed $state, PourcentagePaiement $record) {
                    return $record->banque 
                        ? "{$record->codebanque} ({$record->banque->nomdebanque} {$record->banque->adressedebanque})"
                        : '';
                }),
            
            // Property details
            ExportColumn::make('numappartement')
                ->label('Appartement'),
            
            ExportColumn::make('prixdelogt')
                ->label('Prix Appartement')
                ->formatStateUsing(fn ($state) => number_format($state, 2).' DZD'),
            
            ExportColumn::make('numparking')
                ->label('Parking'),
            
            ExportColumn::make('prixparking')
                ->label('Prix Parking')
                ->formatStateUsing(fn ($state) => number_format($state, 2).' DZD'),
            
            ExportColumn::make('Numlocal')
                ->label('Local'),
            
            ExportColumn::make('prixlocal')
                ->label('Prix Local')
                ->formatStateUsing(fn ($state) => number_format($state, 2).' DZD'),
            
            // Financial summary
            ExportColumn::make('prix_total')
                ->label('Prix Total')
                ->formatStateUsing(fn ($state) => number_format($state, 2).' DZD'),
            
            ExportColumn::make('apport_personel')
                ->label('Apport Personnel')
                ->formatStateUsing(fn ($state) => number_format($state, 2).' DZD'),
            
            ExportColumn::make('credit_bancaire')
                ->label('Crédit Bancaire')
                ->formatStateUsing(fn ($state) => number_format($state, 2).' DZD'),
            
            ExportColumn::make('apport_personel_paye')
                ->label('Apport Personnel Payé')
                ->formatStateUsing(fn ($state) => number_format($state, 2).' DZD'),
            
            ExportColumn::make('credit_bancaire_paye')
                ->label('Crédit Bancaire Payé')
                ->formatStateUsing(fn ($state) => number_format($state, 2).' DZD'),
            
            ExportColumn::make('totalite_paiements')
                ->label('Totalité Paiements')
                ->formatStateUsing(fn ($state) => number_format($state, 2).' DZD'),
            
            // Percentages
            ExportColumn::make('percentage_apport_personel_paye')
                ->label('% Apport Personnel Payé')
                ->formatStateUsing(fn ($state) => number_format($state, 2).'%'),
            
            ExportColumn::make('percentage_credit_bancaire_paye')
                ->label('% Crédit Bancaire Payé')
                ->formatStateUsing(fn ($state) => number_format($state, 2).'%'),
            
            ExportColumn::make('percentage_total_paye')
                ->label('% Total Payé')
                ->formatStateUsing(fn ($state) => number_format($state, 2).'%'),
        ];
    }

    public static function prepareQuery(Builder $query): Builder
    {
        return $query->with(['client', 'banque', 'appartement', 'parking', 'local', 'achat']);
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your pourcentage paiement export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}