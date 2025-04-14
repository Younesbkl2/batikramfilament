<?php

namespace App\Filament\Widgets;

use App\Models\Achat;
use App\Models\Paiement;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverviewGlobal extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';
    protected int $columns = 4;

    protected function getStats(): array
    {
        // Appartement Stats
        $appartementStats = $this->getPropertyStats('numappartement', 'Appartement');
        
        // Parking Stats
        $parkingStats = $this->getPropertyStats('numparking', 'Parking');

        return array_merge($appartementStats, $parkingStats);
    }

    private function getPropertyStats(string $propertyField, string $propertyType): array
    {
        // Total dépense
        $totalDepense = Paiement::whereHas('achat', function ($query) use ($propertyField) {
            $query->whereNotNull($propertyField);
        })->sum('montantpaie');

        // Total a payer
        $totalAPayer = Achat::whereNotNull($propertyField)
            ->with($propertyType)
            ->get()
            ->sum(function ($achat) use ($propertyType) {
                return $achat->{$propertyType}->{"prix{$propertyType}"} ?? 0;
            });

        // Total restant
        $totalRestant = $totalAPayer - $totalDepense;

        // Total transactions
        $totalTransactions = Paiement::whereHas('achat', function ($query) use ($propertyField) {
            $query->whereNotNull($propertyField);
        })->count();

        return [
            Stat::make("Total dépense $propertyType", $this->formatDzd($totalDepense))
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            
            Stat::make("Total montant restant $propertyType", $this->formatDzd($totalRestant))
                ->color($totalRestant > 0 ? 'danger' : 'success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            
            Stat::make("Total a payer $propertyType", $this->formatDzd($totalAPayer))
                ->color('primary')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
            
            Stat::make("Total transactions $propertyType", $totalTransactions)
                ->color('warning')
                ->chart([7, 2, 10, 3, 15, 4, 17]),
        ];
    }

    private function formatDzd($amount): string
    {
        return number_format($amount, 2, ',', ' ') . ' DZD';
    }
}
