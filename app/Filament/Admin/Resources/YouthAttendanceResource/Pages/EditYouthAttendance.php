<?php

namespace App\Filament\Admin\Resources\YouthAttendanceResource\Pages;

use App\Filament\Admin\Resources\YouthAttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditYouthAttendance extends EditRecord
{
    protected static string $resource = YouthAttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = auth()->user();
        $data['user_id'] = $user->id;

        // Auto-fill region for regional officers
        if ($user->hasRole('regional_programs_support_officer')) {
            $data['region_id'] = $user->region_id;
            $data['verified_by'] = $user->name;
        }

        return $data;
    }
}
