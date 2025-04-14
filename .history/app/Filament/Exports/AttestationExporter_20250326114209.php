<?php

namespace App\Filament\Exports;

use App\Models\Attestation;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class AttestationExporter extends Exporter
{
    protected static ?string $model = Attestation::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('ID_ATTESTATION'),
            ExportColumn::make('reservation'),
            ExportColumn::make('reservation_notaire'),
            ExportColumn::make('prestation'),
            ExportColumn::make('remise_des_clés'),
            ExportColumn::make('Num_attestation'),
            ExportColumn::make('codachat'),
            ExportColumn::make('date_attestation'),
            ExportColumn::make('OBS'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
            ExportColumn::make('deleted_at'),
            ExportColumn::make('last_modified_by'),
            ExportColumn::make('deleted_by'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your attestation export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
