<?php

namespace App\Filament\Widgets;

use App\Models\EtatPaiement;
use App\Models\Paiement;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            // Total Paid (using etat_paiement view)
            Card::make('Total Payé', 
                EtatPaiement::where('statue_paiment', 'Payé')->sum('total_depense'))
                ->money('DZD')
                ->color('success'),
            
            // Pending Payments (using etat_paiement view)
            Card::make('En Attente', 
                EtatPaiement::where('statue_paiment', 'PAS Payé')->sum('reste_a_payer'))
                ->money('DZD')
                ->color('danger'),
            
            // Average Transaction (using paiements table)
            Card::make('Moyenne Transaction', 
                Paiement::avg('montantpaie'))
                ->money('DZD')
                ->color('warning'),
            
            // Total Transactions (using paiements table)
            Card::make('Total Transactions', 
                Paiement::count())
                ->color('primary'),
        ];
    }
}