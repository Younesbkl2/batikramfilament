<?php

namespace App\Filament\Widgets;

use App\Models\Paiement;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class MonthlyRevenueFlow extends ChartWidget
{
    protected static ?string $heading = 'Flux de Revenus Mensuels';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // Get selected year from filters
        $selectedYear = $this->filters['year'] ?? now()->year;

        $data = Trend::model(Paiement::class)
            ->dateColumn('datepaie')  // Use correct date column
            ->between(
                start: now()->year($selectedYear)->startOfYear(),
                end: now()->year($selectedYear)->endOfYear(),
            )
            ->perMonth()
            ->sum('montantpaie');

        return [
            'datasets' => [
                [
                    'label' => 'Revenus Mensuels',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#3B82F6',
                    'tension' => 0.4,
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => 
                \Carbon\Carbon::parse($value->date)->format('M Y')),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            'year' => [
                'label' => 'Année',
                'options' => [
                    (string) now()->year => 'Cette Année',
                    (string) now()->subYear()->year => 'L\'Année Dernière',
                ],
            ],
        ];
    }
}