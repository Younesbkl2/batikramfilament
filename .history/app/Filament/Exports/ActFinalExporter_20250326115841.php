<?php

namespace App\Filament\Exports;

use App\Models\ActFinal;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class ActFinalExporter extends Exporter
{
    protected static ?string $model = ActFinal::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            
            ExportColumn::make('codeclient')
                ->formatStateUsing(function (mixed $state, ActFinal $actFinal) {
                    $client = $actFinal->client;
                    return $client 
                        ? "{$actFinal->codeclient} ({$client->nomclient} {$client->prenomclient})"
                        : $actFinal->codeclient;
                }),
            
            ExportColumn::make('codebanque')
                ->formatStateUsing(function (mixed $state, ActFinal $actFinal) {
                    $banque = $actFinal->banque;
                    return $banque
                        ? "{$actFinal->codebanque} ({$banque->nomdebanque} {$banque->adressedebanque})"
                        : $actFinal->codebanque;
                }),
            
            ExportColumn::make('depotcahierplusattestremisecles')
                ->formatStateUsing(fn ($state) => $state ? 'Oui' : 'Non'),
            
            ExportColumn::make('signactfinal')
                ->formatStateUsing(fn ($state) => $state ? 'Oui' : 'Non'),
            
            ExportColumn::make('enrgactfinal')
                ->formatStateUsing(fn ($state) => $state ? 'Oui' : 'Non'),
            
            ExportColumn::make('remisedescles')
                ->formatStateUsing(fn ($state) => $state ? 'Oui' : 'Non'),
            
        ];
    }

    public static function prepareQuery(Builder $query): Builder
    {
        return $query->with(['client', 'banque']);
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your act final export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}