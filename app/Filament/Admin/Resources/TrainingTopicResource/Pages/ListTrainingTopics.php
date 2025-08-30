<?php

namespace App\Filament\Admin\Resources\TrainingTopicResource\Pages;

use App\Filament\Admin\Resources\TrainingTopicResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrainingTopics extends ListRecords
{
    protected static string $resource = TrainingTopicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
