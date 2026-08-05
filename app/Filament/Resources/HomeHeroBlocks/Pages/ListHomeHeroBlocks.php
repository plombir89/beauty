<?php

namespace App\Filament\Resources\HomeHeroBlocks\Pages;

use App\Filament\Resources\HomeHeroBlocks\HomeHeroBlockResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListHomeHeroBlocks extends ListRecords
{
    protected static string $resource = HomeHeroBlockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
