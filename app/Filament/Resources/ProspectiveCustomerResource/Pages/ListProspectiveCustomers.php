<?php

namespace App\Filament\Resources\ProspectiveCustomerResource\Pages;

use App\Filament\Resources\ProspectiveCustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProspectiveCustomers extends ListRecords
{
    protected static string $resource = ProspectiveCustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
