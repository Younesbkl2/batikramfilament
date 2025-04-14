<?php

namespace App\Filament\Exports;

use App\Models\Achat;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Common\Entity\Style\CellAlignment;

class AchatExporter extends Exporter
{
    protected static ?string $model = Achat::class;

    protected static function headerStyle(): Style
    {
        return (new Style())
            ->setBackgroundColor(Color::LIGHT_BLUE)
            ->setFontBold()
            ->setFontSize(12)
            ->setCellAlignment(CellAlignment::CENTER);
    }

    public static function getColumns(): array
    {
        $headerStyle = self::headerStyle();

        return [
            ExportColumn::make('codachat')
                ->headerFormat($headerStyle),

            ExportColumn::make('codeclient')
                ->headerFormat($headerStyle)
                ->formatStateUsing(function (mixed $state, Achat $achat) {
                    $client = $achat->client;
                    return $client 
                        ? "{$achat->codeclient} ({$client->nomclient} {$client->prenomclient})"
                        : $achat->codeclient;
                }),

            ExportColumn::make('codeprod')
                ->headerFormat($headerStyle)
                ->formatStateUsing(function (mixed $state, Achat $achat) {
                    $produit = $achat->produit;
                    return $produit
                        ? "{$achat->codeprod} ({$produit->Typeproduit})"
                        : $achat->codeprod;
                }),

            ExportColumn::make('codebanque')
                ->headerFormat($headerStyle)
                ->formatStateUsing(function (mixed $state, Achat $achat) {
                    $banque = $achat->banque;
                    return $banque
                        ? "{$achat->codebanque} ({$banque->nomdebanque} {$banque->adressedebanque})"
                        : $achat->codebanque;
                }),

            ExportColumn::make('numappartement')
                ->headerFormat($headerStyle),

            ExportColumn::make('numparking')
                ->headerFormat($headerStyle),

            ExportColumn::make('Numlocal')
                ->headerFormat($headerStyle),

            ExportColumn::make('Observations')
                ->headerFormat($headerStyle),

            ExportColumn::make('ID_ATTESTATION')
                ->label('ID_ATTESTATION')
                ->headerFormat($headerStyle),
        ];
    }

    public static function prepareQuery(Builder $query): Builder
    {
        return $query->with([
            'client', 
            'produit',
            'banque',
            'appartement',
            'parking',
            'local'
        ]);
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