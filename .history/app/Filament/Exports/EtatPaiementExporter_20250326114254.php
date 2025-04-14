<?php

namespace App\Filament\Exports;

use App\Models\EtatPaiement;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class EtatPaiementExporter extends Exporter
{
    protected static ?string $model = EtatPaiement::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('codachat'),
            ExportColumn::make('codeclient'),
            ExportColumn::make('numappartement'),
            ExportColumn::make('prix_appartement'),
            ExportColumn::make('numparking'),
            ExportColumn::make('prix_parking'),
            ExportColumn::make('Numlocal'),
            ExportColumn::make('prix_local'),
            ExportColumn::make('total_prix'),
            ExportColumn::make('total_depense'),
            ExportColumn::make('reste_a_payer'),
            ExportColumn::make('statue_paiment'),
            ExportColumn::make('codeprj'),
            ExportColumn::make('code_proprietaire'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your etat paiement export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
