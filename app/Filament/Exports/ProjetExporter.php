<?php

namespace App\Filament\Exports;

use App\Models\Projet;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class ProjetExporter extends Exporter
{
    protected static ?string $model = Projet::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('codeprj')
                ->label('Code Projet'),
            ExportColumn::make('Libelleprj')
                ->label('Libellé du Projet'),
            ExportColumn::make('adresseprj')
                ->label('Adresse du Projet'),
            ExportColumn::make('datedebuttrvx')
                ->label('Date Début Projet')
                ->formatStateUsing(fn ($state): ?string => $state?->format('d-m-Y')),
            ExportColumn::make('datefintrvx')
                ->label('Date Fin Projet')
                ->formatStateUsing(fn ($state): ?string => $state?->format('d-m-Y')),
            ExportColumn::make('code_proprietaire')
                ->label('Propriétaire')
                ->formatStateUsing(function (mixed $state, Projet $projet) {
                    $proprietaire = $projet->proprietaire;
                    return $proprietaire 
                        ? "{$projet->code_proprietaire} ({$proprietaire->nom_proprietaire} {$proprietaire->prenom_proprietaire})"
                        : $projet->code_proprietaire;
                }),
        ];
    }

    public static function prepareQuery(Builder $query): Builder
    {
        return $query->with([
            'proprietaire'
        ]);
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your projet export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}