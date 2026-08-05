<?php

namespace App\Filament\Resources\ExpertisePillars\Pages;

use App\Filament\Resources\ExpertisePillars\ExpertisePillarResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListExpertisePillars extends ListRecords
{
    protected static string $resource = ExpertisePillarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
