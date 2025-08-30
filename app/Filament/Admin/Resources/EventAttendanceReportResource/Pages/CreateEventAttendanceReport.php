<?php

namespace App\Filament\Admin\Resources\EventAttendanceReportResource\Pages;

use App\Filament\Admin\Resources\EventAttendanceReportResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEventAttendanceReport extends CreateRecord
{
    protected static string $resource = EventAttendanceReportResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        return $data;
    }
}
