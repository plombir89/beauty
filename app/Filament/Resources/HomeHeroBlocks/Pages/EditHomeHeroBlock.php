<?php

namespace App\Filament\Resources\HomeHeroBlocks\Pages;

use App\Filament\Resources\HomeHeroBlocks\HomeHeroBlockResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHomeHeroBlock extends EditRecord
{
    protected static string $resource = HomeHeroBlockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
