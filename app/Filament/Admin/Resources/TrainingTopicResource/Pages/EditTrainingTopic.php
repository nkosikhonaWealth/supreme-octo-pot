<?php

namespace App\Filament\Admin\Resources\TrainingTopicResource\Pages;

use App\Filament\Admin\Resources\TrainingTopicResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrainingTopic extends EditRecord
{
    protected static string $resource = TrainingTopicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
