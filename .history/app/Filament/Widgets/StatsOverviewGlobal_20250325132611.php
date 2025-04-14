<?php

namespace App\Filament\Widgets;

use App\Models\Achat;
use App\Models\Paiement;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewGlobal extends BaseWidget
{
    protected function getStats(): array
    {
        // Total dépense across all property types
        $totalDepenseGlobal = Paiement::whereHas('achat', function ($query) {
            $query->whereNotNull('numappartement')
                ->orWhereNotNull('numparking')
                ->orWhereNotNull('Numlocal');
        })->sum('montantpaie');

        // Total a payer across all property types
        $totalAPayerGlobal = Achat::with(['appartement', 'parking', 'local'])
            ->get()
            ->sum(function ($achat) {
                return ($achat->appartement->prixdelogt ?? 0) +
                       ($achat->parking->prixparking ?? 0) +
                       ($achat->local->prixlocal ?? 0);
            });

        // Total restant global
        $totalRestantGlobal = $totalAPayerGlobal - $totalDepenseGlobal;

        // Total transactions across all property types
        $totalTransactionsGlobal = Paiement::whereHas('achat', function ($query) {
            $query->whereNotNull('numappartement')
                ->orWhereNotNull('numparking')
                ->orWhereNotNull('Numlocal');
        })->count();

        return [
            Stat::make('Dépense Globale', $this->formatDzd($totalDepenseGlobal))
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->description('32k increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            
            Stat::make('Montant Restant Global (réservé)', $this->formatDzd($totalRestantGlobal))
                ->color($totalRestantGlobal > 0 ? 'danger' : 'success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            
            Stat::make('Total à Payer Global (réservé)', $this->formatDzd($totalAPayerGlobal))
                ->color('primary')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            
            Stat::make('Transactions Globales', $totalTransactionsGlobal)
                ->color('info')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
        ];
    }

    private function formatDzd($amount): string
    {
        return number_format($amount, 2, ',', ' ') . ' DZD';
    }
}