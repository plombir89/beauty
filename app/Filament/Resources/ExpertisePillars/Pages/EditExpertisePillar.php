<?php

namespace App\Filament\Resources\ExpertisePillars\Pages;

use App\Filament\Resources\ExpertisePillars\ExpertisePillarResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditExpertisePillar extends EditRecord
{
    protected static string $resource = ExpertisePillarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
