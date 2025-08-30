<?php

namespace App\Filament\Admin\Resources\InternalAttendanceResource\Pages;

use App\Filament\Admin\Resources\InternalAttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInternalAttendances extends ListRecords
{
    protected static string $resource = InternalAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
