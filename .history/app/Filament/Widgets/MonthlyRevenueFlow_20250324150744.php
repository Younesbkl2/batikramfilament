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
                    'label' => 'Revenus Mensuels (DZD)',
                    'data' => $data->map(fn (TrendValue $value) => (float)$value->aggregate)->toArray(),
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $data->map(function (TrendValue $value) {
                $date = is_string($value->date) 
                    ? \Carbon\Carbon::parse($value->date) 
                    : $value->date;
                
                return $date->locale('fr')->translatedFormat('M Y');
            })->toArray(),
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