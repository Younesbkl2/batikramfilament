<?php

namespace App\Filament\Exports;

use App\Models\Parking;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class ParkingExporter extends Exporter
{
    protected static ?string $model = Parking::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('numparking')
                ->label('Num Parking'),
            
            ExportColumn::make('surfaceparking')
                ->label('Surface'),
            
            ExportColumn::make('codeprod')
                ->label('Produit')
                ->formatStateUsing(function (mixed $state, Parking $parking) {
                    try {
                        return $parking->produit 
                            ? "{$parking->codeprod} ({$parking->produit->Typeproduit})"
                            : $parking->codeprod;
                    } catch (\Exception $e) {
                        return $parking->codeprod;
                    }
                }),
            
            ExportColumn::make('prixparking')
                ->label('Prix')
                ->formatStateUsing(function ($state) {
                    return is_numeric($state) ? number_format($state, 2) . ' DZD' : '0.00 DZD';
                }),
            
            ExportColumn::make('reservationparking')
                ->label('Réservation')
                ->formatStateUsing(fn ($state) => $state ? 'Oui' : 'Non'),
        ];
    }

    public static function prepareQuery(Builder $query): Builder
    {
        return $query->with(['produit']);
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Votre export des parkings a réussi avec ' . number_format($export->successful_rows) . ' ' . str('ligne')->plural($export->successful_rows) . ' exportées.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('ligne')->plural($failedRowsCount) . ' ont échoué.';
        }

        return $body;
    }
}