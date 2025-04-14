<?php

namespace App\Filament\Widgets;

use App\Models\Achat;
use App\Models\Paiement;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Total dépense Appartement
        $totalDepenseAppartement = Paiement::whereHas('achat', function ($query) {
            $query->whereNotNull('numappartement');
        })->sum('montantpaie');

        // Total a payer Appartement (sum of prixdelogt)
        $totalAPayerAppartement = Achat::whereNotNull('numappartement')
            ->with('appartement')
            ->get()
            ->sum(function ($achat) {
                return $achat->appartement->prixdelogt ?? 0;
            });

        // Total montant restant Appartement
        $totalRestantAppartement = $totalAPayerAppartement - $totalDepenseAppartement;

        // Total transactions Appartement
        $totalTransactionsAppartement = Paiement::whereHas('achat', function ($query) {
            $query->whereNotNull('numappartement');
        })->count();

        return [
            Stat::make('Total dépense Appartement', $this->formatDzd($totalDepenseAppartement))
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            
            Stat::make('Total montant restant Appartement', $this->formatDzd($totalRestantAppartement))
                ->color($totalRestantAppartement > 0 ? 'danger' : 'success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            
            Stat::make('Total a payer Appartement', $this->formatDzd($totalAPayerAppartement))
                ->color('primary')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            
            Stat::make('Total transactions Appartement', $totalTransactionsAppartement)
                ->color('info')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
        ];
    }

    private function formatDzd($amount): string
    {
        return number_format($amount, 2, ',', ' ') . ' DZD';
    }
}