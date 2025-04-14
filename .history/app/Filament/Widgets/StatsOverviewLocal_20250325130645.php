<?php

namespace App\Filament\Widgets;

use App\Models\Achat;
use App\Models\Paiement;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverviewLocal extends BaseWidget
{
    protected function getStats(): array
    {
        // Total dépense Local
        $totalDepenseLocal = Paiement::whereHas('achat', function ($query) {
            $query->whereNotNull('Numlocal');
        })->sum('montantpaie');

        // Total a payer Local (sum of prixlocal)
        $totalAPayerLocal = Achat::whereNotNull('Numlocal')
            ->with('local')
            ->get()
            ->sum(function ($achat) {
                return $achat->local->prixlocal ?? 0;
            });

        // Total montant restant Local
        $totalRestantLocal = $totalAPayerLocal - $totalDepenseLocal;

        // Total transactions Local
        $totalTransactionsLocal = Paiement::whereHas('achat', function ($query) {
            $query->whereNotNull('Numlocal');
        })->count();

        return [
            Stat::make('Total dépense Local', $this->formatDzd($totalDepenseLocal))
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            
            Stat::make('Total montant restant Local', $this->formatDzd($totalRestantLocal))
                ->color($totalRestantLocal > 0 ? 'danger' : 'success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            
            Stat::make('Total a payer Local', $this->formatDzd($totalAPayerLocal))
                ->color('primary')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            
            Stat::make('Total transactions Local', $totalTransactionsLocal)
                ->color('info')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
        ];
    }

    private function formatDzd($amount): string
    {
        return number_format($amount, 2, ',', ' ') . ' DZD';
    }
}
