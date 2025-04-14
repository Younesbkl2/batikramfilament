<?php

namespace App\Filament\Exports;

use App\Models\CreditBancaire;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class CreditBancaireExporter extends Exporter
{
    protected static ?string $model = CreditBancaire::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('codeclient'),
            ExportColumn::make('codebanque'),
            ExportColumn::make('depotdossier'),
            ExportColumn::make('depotdossier_attributed_at'),
            ExportColumn::make('depotdossier_modified_at'),
            ExportColumn::make('comite'),
            ExportColumn::make('comite_attributed_at'),
            ExportColumn::make('comite_modified_at'),
            ExportColumn::make('paiementfrais'),
            ExportColumn::make('paiementfrais_attributed_at'),
            ExportColumn::make('paiementfrais_modified_at'),
            ExportColumn::make('signatureconvenrg'),
            ExportColumn::make('signatureconvenrg_attributed_at'),
            ExportColumn::make('signatureconvenrg_modified_at'),
            ExportColumn::make('dossiertransfnotaire'),
            ExportColumn::make('dossiertransfnotaire_attributed_at'),
            ExportColumn::make('dossiertransfnotaire_modified_at'),
            ExportColumn::make('signvspclientgerant'),
            ExportColumn::make('signvspclientgerant_attributed_at'),
            ExportColumn::make('signvspclientgerant_modified_at'),
            ExportColumn::make('recuperationcheque'),
            ExportColumn::make('recuperationcheque_attributed_at'),
            ExportColumn::make('recuperationcheque_modified_at'),
            ExportColumn::make('enrgvsp'),
            ExportColumn::make('enrgvsp_attributed_at'),
            ExportColumn::make('enrgvsp_modified_at'),
            ExportColumn::make('publicationvsp'),
            ExportColumn::make('publicationvsp_attributed_at'),
            ExportColumn::make('publicationvsp_modified_at'),
            ExportColumn::make('paiementtranches'),
            ExportColumn::make('paiementtranches_attributed_at'),
            ExportColumn::make('paiementtranches_modified_at'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
            ExportColumn::make('deleted_at'),
            ExportColumn::make('last_modified_by'),
            ExportColumn::make('deleted_by'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your credit bancaire export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
