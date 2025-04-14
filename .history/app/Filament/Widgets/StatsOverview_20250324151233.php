<?php



namespace App\Filament\Widgets;

use App\Models\Paiement;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Payé', Paiement::where('statue_paiment', 'Payé')->sum('montantpaie'))
                ->money('DZD')
                ->color('success'),
            
            Card::make('En Attente', Paiement::where('statue_paiment', 'PAS Payé')->sum('montantpaie'))
                ->money('DZD')
                ->color('danger'),
            
            Card::make('Moyenne Transaction', Paiement::avg('montantpaie'))
                ->money('DZD')
                ->color('warning'),
            
            Card::make('Total Transactions', Paiement::count())
                ->color('primary'),
        ];
    }
}