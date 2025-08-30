<?php

namespace App\Filament\Resources\ProspectiveCustomerResource\Pages;

use App\Filament\Resources\ProspectiveCustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProspectiveCustomer extends EditRecord
{
    protected static string $resource = ProspectiveCustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
