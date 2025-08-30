<?php

namespace App\Filament\User\Resources\MonthlyReportResource\Pages;

use App\Filament\User\Resources\MonthlyReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMonthlyReport extends EditRecord
{
    protected static string $resource = MonthlyReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
        ];
    }

    public function mutateFormDataBeforeFill(array $data): array
    {
        if ($this->record->admin_verified) {
            $this->form->disabled();
        }

        return $data;
    }

}
