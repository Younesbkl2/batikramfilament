<?php

namespace App\Filament\Exports;

use App\Models\Appartement;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class AppartementExporter extends Exporter
{
    protected static ?string $model = Appartement::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('numappartement'),
            ExportColumn::make('blocappartement'),
            ExportColumn::make('superficie'),
            ExportColumn::make('etage'),
            ExportColumn::make('coteappartement'),
            ExportColumn::make('codeprod'),
            ExportColumn::make('reservation'),
            ExportColumn::make('prixdelogt'),
            ExportColumn::make('codeprj'),
            ExportColumn::make('code_proprietaire'),
            ExportColumn::make('NumEDD'),
            ExportColumn::make('Numpiece'),
            ExportColumn::make('obs'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your appartement export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
