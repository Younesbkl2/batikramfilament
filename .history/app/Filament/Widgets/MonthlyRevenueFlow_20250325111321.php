<?php

namespace App\Filament\Widgets;

use App\Models\Paiement;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class MonthlyRevenueFlow extends ChartWidget
{
    protected static ?string $heading = 'Monthly Revenue Flow';

    protected function getData(): array
    {
        $selectedYear = (int) ($this->filter ?: now()->year);

        $data = Trend::model(Paiement::class)
            ->between(
                start: now()->year($selectedYear)->startOfYear(),
                end: now()->year($selectedYear)->endOfYear(),
            )
            ->perMonth()
            ->dateColumn('datepaie')
            ->sum('montantpaie');

        return [
            'datasets' => [
                [
                    'label' => 'Montant payé',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#4f46e5',
                    'backgroundColor' => '#4f46e555',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date->format('M')),
        ];
    }

    protected function getFilters(): ?array
    {
        $years = Paiement::query()
            ->whereNotNull('datepaie')
            ->selectRaw('YEAR(datepaie) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        if ($years->isEmpty()) {
            $years = collect([now()->year]);
        }

        return $years->mapWithKeys(fn ($year) => [(string)$year => (string)$year])->toArray();
    }

    protected function getType(): string
    {
        return 'line';
    }
}