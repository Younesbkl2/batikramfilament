<?php

namespace App\Filament\App\Widgets;

use App\Models\EtatPaiement;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PaymentStatusOverview extends ChartWidget
{

    protected static ?int $sort = 5;
    protected static bool $isLazy = false;
    protected static ?string $heading = 'Statut des Paiements';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = EtatPaiement::query()
            ->select('statue_paiment', DB::raw('count(*) as total'))
            ->groupBy('statue_paiment')
            ->pluck('total', 'statue_paiment');

        return [
            'labels' => $data->keys()->map(fn($label) => ucfirst($label))->toArray(),
            'datasets' => [
                [
                    'label' => 'Statut des Paiements',
                    'data' => $data->values()->toArray(),
                    'backgroundColor' => ['#EF4444', '#10B981'],
                    'hoverOffset' => 4
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
