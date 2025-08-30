<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;
use App\Filament\Admin\Widgets\ApplicationStatsOverview;

class ApplicationStatsOverview extends Widget
{
    protected static string $view = 'filament.admin.widgets.application-stats-overview';

    protected function getStats(): array
    {
        return [
            ApplicationStatsOverview\Stat::make('Unique views', '192.1k'),
            ApplicationStatsOverview\Stat::make('Bounce rate', '21%'),
            ApplicationStatsOverview\Stat::make('Average time on page', '3:12'),
        ];
    }
}
