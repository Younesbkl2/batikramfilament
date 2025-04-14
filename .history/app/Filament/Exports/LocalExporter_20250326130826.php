<?php

namespace App\Filament\Exports;

use App\Models\Local;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class LocalExporter extends Exporter
{
    protected static ?string $model = Local::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('Numlocal')
                ->label('Numéro Local'),
            
            ExportColumn::make('surfacelocal')
                ->label('Surface')
                ->numeric(),
            
            ExportColumn::make('codeprod')
                ->label('Produit')
                ->formatStateUsing(function (mixed $state, Local $local) {
                    $produit = $local->produit;
                    return $produit
                        ? "{$local->codeprod} ({$produit->Typeproduit})"
                        : $local->codeprod;
                }),
            
            ExportColumn::make('prixlocal')
                ->label('Prix')
                ->formatStateUsing(fn ($state) => number_format($state, 2).' DZD'),
            
            ExportColumn::make('reservationlocal')
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
        $body = 'Your local export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}