<?php

namespace App\Filament\Exports;

use App\Models\PaiementsDesProprietaires;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PaiementsDesProprietairesExporter extends Exporter
{
    protected static ?string $model = PaiementsDesProprietaires::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('codepaie'),
            ExportColumn::make('codachat'),
            ExportColumn::make('codeclient'),
            ExportColumn::make('nomclient'),
            ExportColumn::make('prenomclient'),
            ExportColumn::make('modepaie'),
            ExportColumn::make('montantpaie'),
            ExportColumn::make('datepaie'),
            ExportColumn::make('codebanque'),
            ExportColumn::make('code_proprietaire'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your paiements des proprietaires export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
