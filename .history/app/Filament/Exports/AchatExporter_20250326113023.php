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

    // Define styling presets
    protected static function headerStyle(): Style
    {
        return (new Style())
            ->setBackgroundColor(Color::LIGHT_BLUE)
            ->setFontBold()
            ->setFontSize(12)
            ->setCellAlignment(CellAlignment::CENTER)
            ->setShouldWrapText(false);
    }

    protected static function highlightStyle(): Style
    {
        return (new Style())
            ->setBackgroundColor(Color::LIGHT_BLUE)
            ->setCellAlignment(CellAlignment::LEFT)
            ->setShouldWrapText(false);
    }

    protected static function defaultStyle(): Style
    {
        return (new Style())
            ->setCellAlignment(CellAlignment::LEFT)
            ->setShouldWrapText(false);
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('codachat')
                ->headerFormat(self::headerStyle())
                ->format(self::defaultStyle())
                ->width(15),

            ExportColumn::make('codeclient')
                ->headerFormat(self::headerStyle())
                ->format(self::highlightStyle())
                ->formatStateUsing(function (mixed $state, Achat $achat) {
                    $client = $achat->client;
                    return $client 
                        ? "{$achat->codeclient} ({$client->nomclient} {$client->prenomclient})"
                        : $achat->codeclient;
                })
                ->width(30),

            ExportColumn::make('codeprod')
                ->headerFormat(self::headerStyle())
                ->format(self::highlightStyle())
                ->formatStateUsing(function (mixed $state, Achat $achat) {
                    $produit = $achat->produit;
                    return $produit
                        ? "{$achat->codeprod} ({$produit->Typeproduit})"
                        : $achat->codeprod;
                })
                ->width(25),

            ExportColumn::make('codebanque')
                ->headerFormat(self::headerStyle())
                ->format(self::highlightStyle())
                ->formatStateUsing(function (mixed $state, Achat $achat) {
                    $banque = $achat->banque;
                    return $banque
                        ? "{$achat->codebanque} ({$banque->nomdebanque} {$banque->adressedebanque})"
                        : $achat->codebanque;
                })
                ->width(35),

            ExportColumn::make('numappartement')
                ->headerFormat(self::headerStyle())
                ->format(self::defaultStyle())
                ->width(18),

            ExportColumn::make('numparking')
                ->headerFormat(self::headerStyle())
                ->format(self::defaultStyle())
                ->width(18),

            ExportColumn::make('Numlocal')
                ->headerFormat(self::headerStyle())
                ->format(self::defaultStyle())
                ->width(18),

            ExportColumn::make('Observations')
                ->headerFormat(self::headerStyle())
                ->format(self::defaultStyle())
                ->width(40),

            ExportColumn::make('ID_ATTESTATION')
                ->label('ID_ATTESTATION')
                ->headerFormat(self::headerStyle())
                ->format(self::defaultStyle())
                ->width(20),
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

    // Auto-adjust column widths based on content
    public function prepareRows(array $rows): array
    {
        $maxLengths = [];
    
        foreach ($rows as $row) {
            foreach ($row as $column => $value) {
                $currentLength = mb_strlen((string) $value);
                if (!isset($maxLengths[$column]) || $currentLength > $maxLengths[$column]) {
                    $maxLengths[$column] = $currentLength;
                }
            }
        }
    
        foreach (self::getColumns() as $column) {
            $columnName = $column->getName();
            if (isset($maxLengths[$columnName])) {
                $buffer = 5; // Add 5-character buffer
                $calculatedWidth = min($maxLengths[$columnName] + $buffer, 50);
                $column->width($calculatedWidth);
            }
        }
    
        return $rows;
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