<?php

namespace App\Filament\Admin\Resources\EventAttendanceReportResource\Pages;

use App\Filament\Admin\Resources\EventAttendanceReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEventAttendanceReport extends EditRecord
{
    protected static string $resource = EventAttendanceReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
