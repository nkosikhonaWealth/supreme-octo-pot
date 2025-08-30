<?php

namespace App\Filament\Admin\Resources\YouthAttendanceResource\Pages;

use App\Filament\Admin\Resources\YouthAttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListYouthAttendances extends ListRecords
{
    protected static string $resource = YouthAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
