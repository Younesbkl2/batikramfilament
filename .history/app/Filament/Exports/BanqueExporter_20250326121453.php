<?php

namespace App\Filament\Exports;

use App\Models\Banque;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class BanqueExporter extends Exporter
{
    protected static ?string $model = Banque::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('codebanque'),
            ExportColumn::make('nomdebanque'),
            ExportColumn::make('adressedebanque'),
            ExportColumn::make('numdecompte'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your banque export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}