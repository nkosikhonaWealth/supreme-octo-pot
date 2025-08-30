<?php

namespace App\Filament\Admin\Resources\YouthMessageResource\Pages;

use App\Filament\Admin\Resources\YouthMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListYouthMessages extends ListRecords
{
    protected static string $resource = YouthMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
