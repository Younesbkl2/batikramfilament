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
        $currentMonth = now()->month;
        $currentYear = now()->year;

        return [
            // Total Monthly Revenue (Current Month)
            Stat::make('Revenu Mensuel Total', 
                Paiement::whereMonth('datepaie', $currentMonth)
                    ->whereYear('datepaie', $currentYear)
                    ->sum('montantpaie'))
                ->money('DZD')
                ->color('success')
                ->description('Ce mois-ci')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->descriptionIcon('heroicon-m-arrow-trending-up'),

            // Average Transaction Value
            Stat::make('Moyenne des Transactions',
                Paiement::avg('montantpaie'))
                ->money('DZD')
                ->color('warning')
                ->description('Toutes les transactions')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            
            // Pending Payments
            Stat::make('Paiements En Attente',
                EtatPaiement::where('statue_paiment', 'PAS Payé')
                    ->sum('reste_a_payer'))
                ->money('DZD')
                ->color('danger')
                ->description('Solde impayé total')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            
            // Properties Sold MTD (Month-to-Date)
            Stat::make('Propriétés Vendues (MTD)',
                EtatPaiement::whereMonth('created_at', $currentMonth)
                    ->whereYear('created_at', $currentYear)
                    ->count())
                ->color('primary')
                ->description('Ce mois-ci')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->descriptionIcon('heroicon-m-home-modern'),
        ];
    }
}