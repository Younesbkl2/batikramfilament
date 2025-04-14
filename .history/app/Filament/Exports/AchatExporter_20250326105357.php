<?php

namespace App\Filament\Exports;

use App\Models\Achat;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class AchatExporter extends Exporter
{
    protected static ?string $model = Achat::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('codachat'),
            ExportColumn::make('codeclient')
            ->formatStateUsing(fn ($record) => "{$record->codeclient} ({$record->client->nomclient} {$record->client->prenomclient})"),
            ExportColumn::make('codeprod')
            ->formatStateUsing(fn ($record) => "{$record->codeprod} ({$record->produit->Typeproduit})"),
            ExportColumn::make('codebanque')
            ->formatStateUsing(fn ($record) => "{$record->codebanque} ({$record->banque->nomdebanque} {$record->banque->adressedebanque})"),
            ExportColumn::make('numappartement'),
            ExportColumn::make('numparking'),
            ExportColumn::make('Numlocal'),
            ExportColumn::make('Observations'),
            ExportColumn::make('ID_ATTESTATION')
            ->label('ID_ATTESTATION'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your achat export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
