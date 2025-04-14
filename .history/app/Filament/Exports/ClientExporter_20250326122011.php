<?php

namespace App\Filament\Exports;

use App\Models\Client;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ClientExporter extends Exporter
{
    protected static ?string $model = Client::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('codeclient'),
            ExportColumn::make('nomclient'),
            ExportColumn::make('prenomclient'),
            ExportColumn::make('adresseclient'),
            ExportColumn::make('Numdetel')->label('Tél 1'),
            ExportColumn::make('NUM_TEL')->label('Tél 2'),
            ExportColumn::make('email'),
            ExportColumn::make('photo')
                ->formatStateUsing(fn ($state) => $state ? asset('storage/' . $state) : null),
            ExportColumn::make('dossier')
                ->formatStateUsing(fn ($state) => $state ? asset('storage/' . $state) : null),
            ExportColumn::make('date_de_naissance')
                ->formatStateUsing(fn ($state) => $state?->format('d-m-Y')),
            ExportColumn::make('Vsp_publié')
                ->formatStateUsing(fn ($state) => $state ? 'Oui' : 'Non'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your client export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}