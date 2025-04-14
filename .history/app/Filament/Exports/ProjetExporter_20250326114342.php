<?php

namespace App\Filament\Exports;

use App\Models\Projet;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ProjetExporter extends Exporter
{
    protected static ?string $model = Projet::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('codeprj'),
            ExportColumn::make('Libelleprj'),
            ExportColumn::make('adresseprj'),
            ExportColumn::make('datedebuttrvx'),
            ExportColumn::make('datefintrvx'),
            ExportColumn::make('code_proprietaire'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
            ExportColumn::make('deleted_at'),
            ExportColumn::make('last_modified_by'),
            ExportColumn::make('deleted_by'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your projet export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
