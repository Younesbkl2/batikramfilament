<?php

namespace App\Filament\Exports;

use App\Models\Paiement;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PaiementExporter extends Exporter
{
    protected static ?string $model = Paiement::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('codepaie'),
            ExportColumn::make('modepaie'),
            ExportColumn::make('codebanque'),
            ExportColumn::make('montantpaie'),
            ExportColumn::make('datepaie'),
            ExportColumn::make('codeclient'),
            ExportColumn::make('codachat'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
            ExportColumn::make('deleted_at'),
            ExportColumn::make('last_modified_by'),
            ExportColumn::make('deleted_by'),
        ];
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
