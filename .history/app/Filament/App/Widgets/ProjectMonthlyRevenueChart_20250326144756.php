<?php

namespace App\Filament\App\Widgets;

use Filament\Widgets\ChartWidget;

class ProjectMonthlyRevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            //
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
