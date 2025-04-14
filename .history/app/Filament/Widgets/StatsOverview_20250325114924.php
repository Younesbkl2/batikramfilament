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
                ->money('DZD')
                ->color('success'),
            
            // Pending Payments
            Stat::make('En Attente', 
                EtatPaiement::where('statue_paiment', 'PAS Payé')->sum('reste_a_payer'))
                ->money('DZD')
                ->color('danger'),
            
            // Average Transaction
            Stat::make('Moyenne Transaction', 
                number_format(Paiement::avg('montantpaie'), 2))
                ->money('DZD')
                ->color('warning'),
            
            // Total Transactions
            Stat::make('Total Transactions', 
                Paiement::count())
                ->color('primary'),
        ];
    }
}