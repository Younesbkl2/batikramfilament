<?php

namespace App\Filament\Exports;

use App\Models\EtatPaiementCredit;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class EtatPaiementCreditExporter extends Exporter
{
    protected static ?string $model = EtatPaiementCredit::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('codachat'),
            ExportColumn::make('codeclient'),
            ExportColumn::make('nomclient'),
            ExportColumn::make('prenomclient'),
            ExportColumn::make('Numdetel'),
            ExportColumn::make('NUM_TEL'),
            ExportColumn::make('numappartement'),
            ExportColumn::make('codeprj'),
            ExportColumn::make('code_proprietaire'),
            ExportColumn::make('Observations'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your etat paiement credit export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
