<?php

namespace App\Filament\Widgets;

use App\Models\EtatPaiement;
use App\Models\Paiement;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalPaye = EtatPaiement::where('statue_paiment', 'Payé')->sum('total_depense');
        $enAttente = EtatPaiement::where('statue_paiment', 'PAS Payé')->sum('reste_a_payer');
        $averageTransaction = Paiement::avg('montantpaie') ?? 0;

        return [
            Stat::make('Total Payé', $this->formatDzd($totalPaye))
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            
            Stat::make('En Attente', $this->formatDzd($enAttente))
                ->color('danger')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            
            Stat::make('Moyenne Transaction', $this->formatDzd($averageTransaction))
                ->color('warning')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            
            Stat::make('Total Transactions', Paiement::count())
                ->color('primary')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
        ];
    }

    private function formatDzd($amount): string
    {
        return number_format($amount, 2, ',', ' ') . ' DZD';
    }
}