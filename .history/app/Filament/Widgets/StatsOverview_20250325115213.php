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
                ->currency('DZD')
                ->color('success'),
            
            // Pending Payments
            Stat::make('En Attente', 
                EtatPaiement::where('statue_paiment', 'PAS Payé')->sum('reste_a_payer'))
                ->currency('DZD')
                ->color('danger'),
            
            // Average Transaction
            Stat::make('Moyenne Transaction', 
                number_format(Paiement::avg('montantpaie'), 2))
                ->currency('DZD')
                ->color('warning'),
            
            // Total Transactions
            Stat::make('Total Transactions', 
                Paiement::count())
                ->color('primary'),
        ];
    }
}