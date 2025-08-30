<?php

namespace App\Filament\Admin\Resources\InternalAttendanceResource\Pages;

use App\Filament\Admin\Resources\InternalAttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInternalAttendance extends CreateRecord
{
    protected static string $resource = InternalAttendanceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();

        // Ensure region is set
        if ($user->hasRole('regional_programs_support_officer')) {
            $data['region'] = $user->region->name;
            $data['verified_by'] = $user->name;
        }

        $data['user_id'] = $user->id;

        return $data;
    }
}
