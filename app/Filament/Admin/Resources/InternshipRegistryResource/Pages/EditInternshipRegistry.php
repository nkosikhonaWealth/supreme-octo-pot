<?php

namespace App\Filament\Admin\Resources\InternshipRegistryResource\Pages;

use App\Filament\Admin\Resources\InternshipRegistryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInternshipRegistry extends EditRecord
{
    protected static string $resource = InternshipRegistryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
