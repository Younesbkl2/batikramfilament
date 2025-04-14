<?php

namespace App\Filament\Widgets;

use App\Models\EtatPaiement;
use App\Models\Paiement;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalPaye = EtatPaiement::where('statue_paiment', 'Payé')->sum('total_depense');
        $enAttente = EtatPaiement::where('statue_paiment', 'PAS Payé')->sum('reste_a_payer');
        $averageTransaction = Paiement::avg('montantpaie') ?? 0;

        return [
            Stat::make('Total Payé', $totalPaye)
                ->color('success')
                ->formatStateUsing(fn ($state) => number_format($state, 2) . ' DZD')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            
            Stat::make('En Attente', $enAttente)
                ->color('danger')
                ->formatStateUsing(fn ($state) => number_format($state, 2) . ' DZD')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            
            Stat::make('Moyenne Transaction', $averageTransaction)
                ->color('warning')
                ->formatStateUsing(fn ($state) => number_format($state, 2) . ' DZD')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            
            Stat::make('Total Transactions', Paiement::count())
                ->color('primary')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
        ];
    }
}