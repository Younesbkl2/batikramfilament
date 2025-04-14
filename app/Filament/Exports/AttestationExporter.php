<?php

namespace App\Filament\Exports;

use App\Models\Attestation;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class AttestationExporter extends Exporter
{
    protected static ?string $model = Attestation::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('ID_ATTESTATION')
                ->label('ID_ATTESTATION'),
            
            ExportColumn::make('reservation')
                ->formatStateUsing(fn ($state) => $state ? 'Oui' : 'Non'),
            
            ExportColumn::make('reservation_notaire')
                ->formatStateUsing(fn ($state) => $state ? 'Oui' : 'Non'),
            
            ExportColumn::make('prestation')
                ->formatStateUsing(fn ($state) => $state ? 'Oui' : 'Non'),
            
            ExportColumn::make('remise_des_clés')
                ->formatStateUsing(fn ($state) => $state ? 'Oui' : 'Non'),
            
            ExportColumn::make('Num_attestation'),
            
            ExportColumn::make('codachat'),
            
            ExportColumn::make('date_attestation')
                ->formatStateUsing(fn ($state) => $state?->format('d-m-Y')),
            
            ExportColumn::make('OBS')
                ->label('OBS'),
        ];
    }

    public static function prepareQuery(Builder $query): Builder
    {
        return $query->with(['achat']); // Add if you need achat relationship data
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your attestation export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}