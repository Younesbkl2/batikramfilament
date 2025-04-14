<?php

namespace App\Filament\Widgets;

use App\Models\Paiement;
use App\Models\Projet;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class ProjectMonthlyRevenueChart extends ChartWidget
{
    protected static ?int $sort = 3;
    protected static bool $isLazy = false;
    protected static ?string $heading = 'Revenus mensuels des appartements par projet';
    protected static ?string $maxHeight = '400px';

    protected function getData(): array
    {
        Carbon::setLocale('fr');
        $selectedYear = (int) ($this->filter ?: now()->year);

        // Get all projects with payments
        $projects = Projet::whereHas('appartements.achats.paiements')
            ->with('appartements.achats.paiements')
            ->get();

        $datasets = [];
        $colors = ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6']; // Different colors for projects

        foreach ($projects as $index => $project) {
            // Get payments for this project through appartements and achats
            $data = Trend::query(
                Paiement::whereHas('achat.appartement', function ($query) use ($project) {
                    $query->where('codeprj', $project->codeprj);
                }))
                ->between(
                    start: Carbon::create($selectedYear)->startOfYear(),
                    end: Carbon::create($selectedYear)->endOfYear(),
                )
                ->perMonth()
                ->dateColumn('datepaie')
                ->sum('montantpaie');

            // Map data to months
            $monthlyData = collect(range(1, 12))->mapWithKeys(fn ($month) => [$month => 0]);
            
            foreach ($data as $value) {
                $month = Carbon::parse($value->date)->month;
                $monthlyData[$month] = $value->aggregate;
            }

            $datasets[] = [
                'label' => $project->Libelleprj . ' (' . $project->adresseprj . ')', // Combined label
                'data' => $monthlyData->values(),
                'borderColor' => $colors[$index % count($colors)],
                'backgroundColor' => $colors[$index % count($colors)] . '33',
                'tension' => 0.4,
            ];
        }

        // Generate month labels
        $labels = collect(range(1, 12))->map(function ($month) use ($selectedYear) {
            return Carbon::create($selectedYear, $month)->translatedFormat('M');
        });

        return [
            'datasets' => $datasets,
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