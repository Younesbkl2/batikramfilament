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
        $data = Trend::model(Paiement::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->sum('montantpaie');

        return [
            'datasets' => [
                [
                    'label' => 'Revenus Mensuels',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate)->toArray(),
                    'borderColor' => '#3B82F6',
                    'tension' => 0.4,
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => 
                is_array($value->date) ? json_encode($value->date) : (string) $value->date
            )->toArray(), // 🔧 FIX: Convert labels to proper strings
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
                'default' => (string) now()->year,
            ],
        ];
    }
}
