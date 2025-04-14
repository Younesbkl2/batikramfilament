<?php

namespace App\Filament\Exports;

use App\Models\Produit;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class ProduitExporter extends Exporter
{
    protected static ?string $model = Produit::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('codeprod')
                ->label('Code Produit'),
            ExportColumn::make('Typeproduit')
                ->label('Type de Produit'),
            ExportColumn::make('code_proprietaire')
                ->label('Propriétaire')
                ->formatStateUsing(function (mixed $state, Produit $produit) {
                    $proprietaire = $produit->proprietaire;
                    return $proprietaire 
                        ? "{$produit->code_proprietaire} ({$proprietaire->nom_proprietaire} {$proprietaire->prenom_proprietaire})"
                        : $produit->code_proprietaire;
                }),
        ];
    }

    public static function prepareQuery(Builder $query): Builder
    {
        return $query->with([
            'proprietaire',
            'lastModifiedBy',
            'deletedBy'
        ]);
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your produit export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}