<?php

namespace App\Filament\Resources\ConsultationSlotResource\Pages;

use App\Filament\Resources\ConsultationSlotResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConsultationSlot extends EditRecord
{
    protected static string $resource = ConsultationSlotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
