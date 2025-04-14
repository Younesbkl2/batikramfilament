<?php

namespace App\Filament\Widgets;

use App\Models\Paiement;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class MonthlyRevenueFlow extends ChartWidget
{
    protected static ?string $heading = 'Monthly Revenue Flow';

    protected function getData(): array
    {
        $selectedYear = (int) ($this->filter ?: now()->year);

        $data = Trend::model(Paiement::class)
            ->between(
                start: Carbon::create($selectedYear)->startOfYear(),
                end: Carbon::create($selectedYear)->endOfYear(),
            )
            ->perMonth()
            ->dateColumn('datepaie')
            ->sum('montantpaie');

        // Generate labels for all months
        $labels = collect(range(1, 12))->map(function ($month) use ($selectedYear) {
            return Carbon::create($selectedYear, $month)->format('M');
        });

        // Map data to correct months
        $monthlyData = collect(range(1, 12))->mapWithKeys(function ($month) {
            return [$month => 0];
        });

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
}