<?php

namespace App\Filament\Admin\Resources\EventAttendanceReportResource\Pages;

use App\Filament\Admin\Resources\EventAttendanceReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEventAttendanceReports extends ListRecords
{
    protected static string $resource = EventAttendanceReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
