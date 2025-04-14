<?php



namespace App\Filament\Widgets;


use App\Models\EtatPaiement;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PaymentStatusOverview extends ChartWidget
{
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
                    'backgroundColor' => ['#10B981', '#EF4444'],
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
