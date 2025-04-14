<?php

namespace App\Filament\Exports;

use App\Models\Parking;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ParkingExporter extends Exporter
{
    protected static ?string $model = Parking::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('numparking'),
            ExportColumn::make('surfaceparking'),
            ExportColumn::make('codeprod'),
            ExportColumn::make('prixparking'),
            ExportColumn::make('reservationparking'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your parking export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
