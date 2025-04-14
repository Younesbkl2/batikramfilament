<?php

namespace App\Filament\Widgets;

use Filament\Widgets\LineChartWidget;
use App\Models\Paiement;
use Illuminate\Support\Facades\DB;
use Filament\Forms;

class MonthlyRevenueFlow extends LineChartWidget
{
    protected static ?string $heading = 'Paiements Mensuels';
    protected static ?int $sort = 1; // Change sort order if needed

    public ?string $year = null;
    public ?string $modepaie = null;

    protected function getData(): array
    {
        // Default year is the current year
        $year = $this->year ?? now()->year;

        // Query payments, grouping by month and summing montantpaie
        $query = Paiement::select(
            DB::raw('MONTH(datepaie) as month'),
            DB::raw('SUM(montantpaie) as total')
        )
        ->whereYear('datepaie', $year)
        ->when($this->modepaie, fn ($q) => $q->where('modepaie', $this->modepaie))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Prepare data for the chart
        $data = array_fill(0, 12, 0);
        foreach ($query as $row) {
            $data[$row->month - 1] = $row->total;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Montant Payé (DZD)',
                    'data' => $data,
                    'borderColor' => '#4F46E5',
                    'backgroundColor' => 'rgba(79, 70, 229, 0.2)',
                    'fill' => true,
                ],
            ],
            'labels' => [
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
            ],
        ];
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('year')
                ->label('Année')
                ->options($this->getFilters()['year'])
                ->default(now()->year)
                ->reactive(), // Reactively update chart on change
    
            Forms\Components\Select::make('modepaie')
                ->label('Mode de Paiement')
                ->options(fn () => $this->getFilters()['modepaie']) // Fetch dynamically
                ->nullable()
                ->reactive(), // Update chart when changed
        ];
    }

    protected function getFilters(): ?array
    {
        $filters = [
            'year' => collect(range(now()->year - 5, now()->year))
                ->mapWithKeys(fn ($year) => [(string) $year => (string) $year])
                ->toArray(),
    
            'modepaie' => Paiement::distinct()
                ->whereNotNull('modepaie')
                ->pluck('modepaie')
                ->mapWithKeys(fn ($mode) => [$mode => $mode]) // Converts to key-value array
                ->toArray(),
        ];
    
        dd($filters, $filters['modepaie']);
    
        return $filters;
    }
     
}
