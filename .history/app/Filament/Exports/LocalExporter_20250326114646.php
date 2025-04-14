<?php

namespace App\Filament\Exports;

use App\Models\Local;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class LocalExporter extends Exporter
{
    protected static ?string $model = Local::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('Numlocal'),
            ExportColumn::make('surfacelocal'),
            ExportColumn::make('codeprod'),
            ExportColumn::make('prixlocal'),
            ExportColumn::make('reservationlocal'),
        ];
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
