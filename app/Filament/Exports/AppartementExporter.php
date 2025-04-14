<?php

namespace App\Filament\Exports;

use App\Models\Appartement;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class AppartementExporter extends Exporter
{
    protected static ?string $model = Appartement::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('numappartement'),
            ExportColumn::make('blocappartement'),
            ExportColumn::make('superficie'),
            ExportColumn::make('etage'),
            ExportColumn::make('coteappartement'),
            ExportColumn::make('codeprod')
                ->formatStateUsing(function (mixed $state, Appartement $appartement) {
                    $produit = $appartement->produit;
                    return $produit
                        ? "{$appartement->codeprod} ({$produit->Typeproduit})"
                        : $appartement->codeprod;
                }),
            ExportColumn::make('reservation')
                ->formatStateUsing(fn ($state) => $state ? 'Oui' : 'Non'),
            ExportColumn::make('prixdelogt')
                ->formatStateUsing(fn ($state) => number_format($state, 2).' DZD'),
            ExportColumn::make('codeprj')
                ->formatStateUsing(function (mixed $state, Appartement $appartement) {
                    $projet = $appartement->projet;
                    return $projet
                        ? "{$appartement->codeprj} ({$projet->Libelleprj} {$projet->adresseprj})"
                        : $appartement->codeprj;
                }),
            ExportColumn::make('code_proprietaire')
                ->formatStateUsing(function (mixed $state, Appartement $appartement) {
                    $proprietaire = $appartement->proprietaire;
                    return $proprietaire
                        ? "{$appartement->code_proprietaire} ({$proprietaire->nom_proprietaire} {$proprietaire->prenom_proprietaire})"
                        : $appartement->code_proprietaire;
                }),
            ExportColumn::make('NumEDD'),
            ExportColumn::make('Numpiece'),
            ExportColumn::make('obs'),
        ];
    }

    public static function prepareQuery(Builder $query): Builder
    {
        return $query->with(['produit', 'projet', 'proprietaire']);
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your appartement export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}