<?php

namespace App\Filament\Exports;

use App\Models\Proprietaire;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class ProprietaireExporter extends Exporter
{
    protected static ?string $model = Proprietaire::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('code_proprietaire')
                ->label('Code Propriétaire'),
            ExportColumn::make('nom_proprietaire')
                ->label('Nom'),
            ExportColumn::make('prenom_proprietaire')
                ->label('Prénom'),
        ];
    }


    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your proprietaire export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}