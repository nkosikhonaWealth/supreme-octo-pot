<?php

namespace App\Filament\Admin\Resources\InternalAttendanceResource\Pages;

use App\Filament\Admin\Resources\InternalAttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInternalAttendance extends EditRecord
{
    protected static string $resource = InternalAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = auth()->user();

        if ($user->hasRole('regional_programs_support_officer')) {
            $data['region'] = $user->region->name;
            $data['verified_by'] = $user->name;
        }

        return $data;
    }
}
