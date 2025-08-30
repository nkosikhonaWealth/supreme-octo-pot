<?php

namespace App\Filament\Admin\Resources\ToolkitVerificationResource\Pages;

use App\Filament\Admin\Resources\ToolkitVerificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListToolkitVerifications extends ListRecords
{
    protected static string $resource = ToolkitVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
