<?php

namespace App\Filament\Widgets;

use App\Models\Paiement;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class MonthlyRevenueFlow extends ChartWidget
{
    protected static ?string $heading = 'Monthly Revenue Flow';

    protected function getData(): array
    {
        $selectedYear = $this->filter ?: now()->year;

        $data = Trend::model(Paiement::class)
            ->between(
                start: now()->year($selectedYear)->startOfYear(),
                end: now()->year($selectedYear)->endOfYear(),
            )
            ->perMonth()
            ->sum('montantpaie')
            ->dateColumn('datepaie')
            ->includeEmpty();

        return [
            'datasets' => [
                [
                    'label' => 'Montant payé',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#4f46e5', // Add your preferred chart color
                    'backgroundColor' => '#4f46e555', // Add transparency for fill
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => \Carbon\Carbon::parse($value->date)->format('M')),
        ];
    }

    protected function getFilters(): ?array
    {
        $years = Paiement::query()
            ->selectRaw('YEAR(datepaie) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        if ($years->isEmpty()) {
            $years = collect([now()->year]);
        }

        return $years->mapWithKeys(fn ($year) => [$year => (string)$year])->toArray();
    }

    protected function getType(): string
    {
        return 'line';
    }
}