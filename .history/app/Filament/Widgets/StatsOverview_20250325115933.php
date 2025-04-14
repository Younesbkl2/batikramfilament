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
        return [
            // Total Paid
            Stat::make('Total Payé', 
                EtatPaiement::where('statue_paiment', 'Payé')->sum('total_depense'))
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            
            // Pending Payments
            Stat::make('En Attente', 
                EtatPaiement::where('statue_paiment', 'PAS Payé')->sum('reste_a_payer'))
                ->color('danger')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            
            // Average Transaction
            Stat::make('Moyenne Transaction', 
                number_format(Paiement::avg('montantpaie'), 2))
                ->color('warning')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            
            // Total Transactions
            Stat::make('Total Transactions', 
                Paiement::count())
                ->color('primary')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
        ];
    }
}