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
        $averageTransaction = Paiement::avg('montantpaie') ?? 0;

        return [
            Stat::make('Total Payé', 
                EtatPaiement::where('statue_paiment', 'Payé')->sum('total_depense') / 100)
                ->currency('DZD')
                ->color('success')
                ->description('Paiements complétés')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('En Attente', 
                EtatPaiement::where('statue_paiment', 'PAS Payé')->sum('reste_a_payer') / 100)
                ->currency('DZD')
                ->color('danger')
                ->description('Solde restant')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Moyenne Transaction', 
                number_format($averageTransaction, 2))
                ->currency('DZD')
                ->color('warning')
                ->description('Moyenne par transaction')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Total Transactions', 
                Paiement::count())
                ->color('primary')
                ->description('Nombre total de transactions')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
        ];
    }
}