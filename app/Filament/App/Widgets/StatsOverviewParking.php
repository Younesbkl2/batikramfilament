<?php

namespace App\Filament\App\Widgets;

use App\Models\Achat;
use App\Models\Paiement;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverviewParking extends BaseWidget
{

    protected static ?int $sort = 7;
    protected static bool $isLazy = false;
    protected function getStats(): array
    {
        // Total dépense Parking
        $totalDepenseParking = Paiement::whereHas('achat', function ($query) {
            $query->whereNotNull('numparking');
        })->sum('montantpaie');

        // Total a payer Parking (sum of prixparking)
        $totalAPayerParking = Achat::whereNotNull('numparking')
            ->with('parking')
            ->get()
            ->sum(function ($achat) {
                return $achat->parking->prixparking ?? 0;
            });

        // Total montant restant Parking
        $totalRestantParking = $totalAPayerParking - $totalDepenseParking;

        // Total transactions Parking
        $totalTransactionsParking = Paiement::whereHas('achat', function ($query) {
            $query->whereNotNull('numparking');
        })->count();

        return [
            Stat::make('Total dépense Parking', $this->formatDzd($totalDepenseParking))
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            
            Stat::make('Montant total restant Parking (réservé)', $this->formatDzd($totalRestantParking))
                ->color($totalRestantParking > 0 ? 'danger' : 'success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            
            Stat::make('Total a payer Parking (réservé)', $this->formatDzd($totalAPayerParking))
                ->color('primary')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            
            Stat::make('Total transactions Parking', $totalTransactionsParking)
                ->color('info')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
        ];
    }

    private function formatDzd($amount): string
    {
        return number_format($amount, 2, ',', ' ') . ' DZD';
    }
}
