<?php

namespace App\Filament\App\Widgets;

use App\Models\Paiement;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class MonthlyRevenueFlow extends ChartWidget
{
    protected static ?int $sort = 2;
    protected static bool $isLazy = false;
    protected static ?string $heading = 'Flux de revenus mensuels';

    protected function getData(): array
    {
        // Set Carbon locale to French
        Carbon::setLocale('fr');
        
        $selectedYear = (int) ($this->filter ?: now()->year);

        $data = Trend::model(Paiement::class)
            ->between(
                start: Carbon::create($selectedYear)->startOfYear(),
                end: Carbon::create($selectedYear)->endOfYear(),
            )
            ->perMonth()
            ->dateColumn('datepaie')
            ->sum('montantpaie');

        // Generate labels for all months in French
        $labels = collect(range(1, 12))->map(function ($month) use ($selectedYear) {
            return Carbon::create($selectedYear, $month)->translatedFormat('M');
        });

        // Map data to correct months
        $monthlyData = collect(range(1, 12))->mapWithKeys(fn ($month) => [$month => 0]);

        foreach ($data as $value) {
            $month = Carbon::parse($value->date)->month;
            $monthlyData[$month] = $value->aggregate;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Montant payé',
                    'data' => $monthlyData->values(),
                    'borderColor' => '#4f46e5',
                    'backgroundColor' => '#4f46e555',
                ],
            ],
            'labels' => $labels->values(),
        ];
    }

    protected function getFilters(): ?array
    {
        $years = Paiement::query()
            ->whereNotNull('datepaie')
            ->selectRaw('YEAR(datepaie) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->map(fn ($year) => (string)$year);

        if ($years->isEmpty()) {
            $years = collect([(string)now()->year]);
        }

        return $years->mapWithKeys(fn ($year) => [$year => $year])->toArray();
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Montant (DZD)',
                    ],
                ],
            ],
        ];
    }
}
