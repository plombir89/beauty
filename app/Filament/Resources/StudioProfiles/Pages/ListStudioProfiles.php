<?php

namespace App\Filament\Resources\StudioProfiles\Pages;

use App\Filament\Resources\StudioProfiles\StudioProfileResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListStudioProfiles extends ListRecords
{
    protected static string $resource = StudioProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
