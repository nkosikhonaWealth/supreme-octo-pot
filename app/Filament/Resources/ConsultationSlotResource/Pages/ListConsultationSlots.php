<?php

namespace App\Filament\Resources\ConsultationSlotResource\Pages;

use App\Filament\Resources\ConsultationSlotResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConsultationSlots extends ListRecords
{
    protected static string $resource = ConsultationSlotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
