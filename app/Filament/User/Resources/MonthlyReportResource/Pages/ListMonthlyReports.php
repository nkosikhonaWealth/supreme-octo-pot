<?php

namespace App\Filament\User\Resources\MonthlyReportResource\Pages;

use App\Filament\User\Resources\MonthlyReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMonthlyReports extends ListRecords
{
    protected static string $resource = MonthlyReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
        ];
    }
}
