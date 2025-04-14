<?php

namespace App\Filament\Exports;

use App\Models\ActFinal;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ActFinalExporter extends Exporter
{
    protected static ?string $model = ActFinal::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('codeclient'),
            ExportColumn::make('codebanque'),
            ExportColumn::make('depotcahierplusattestremisecles'),
            ExportColumn::make('depotcahierplusattestremisecles_attributed_at'),
            ExportColumn::make('depotcahierplusattestremisecles_modified_at'),
            ExportColumn::make('signactfinal'),
            ExportColumn::make('signactfinal_attributed_at'),
            ExportColumn::make('signactfinal_modified_at'),
            ExportColumn::make('enrgactfinal'),
            ExportColumn::make('enrgactfinal_attributed_at'),
            ExportColumn::make('enrgactfinal_modified_at'),
            ExportColumn::make('remisedescles'),
            ExportColumn::make('remisedescles_attributed_at'),
            ExportColumn::make('remisedescles_modified_at'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
            ExportColumn::make('deleted_at'),
            ExportColumn::make('last_modified_by'),
            ExportColumn::make('deleted_by'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your act final export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
