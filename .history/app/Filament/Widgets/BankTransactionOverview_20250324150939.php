<?php

namespace App\Filament\Widgets;


use App\Models\Paiement;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class BankTransactionOverview extends ChartWidget
{
    protected static ?string $heading = 'Transactions par Banque';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = Paiement::query()
            ->join('banques', 'paiements.codebanque', '=', 'banques.codebanque')
            ->select('banques.nomdebanque', DB::raw('SUM(montantpaie) as total'))
            ->groupBy('banques.nomdebanque')
            ->get();

        return [
            'labels' => $data->pluck('nomdebanque')->toArray(),
            'datasets' => [
                [
                    'label' => 'Transactions',
                    'data' => $data->pluck('total')->toArray(),
                    'backgroundColor' => ['#6366F1', '#EC4899', '#F59E0B', '#10B981'],
                    'hoverOffset' => 4
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
