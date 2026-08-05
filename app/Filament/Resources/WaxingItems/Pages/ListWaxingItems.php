<?php

namespace App\Filament\Resources\WaxingItems\Pages;

use App\Filament\Resources\WaxingItems\WaxingItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWaxingItems extends ListRecords
{
    protected static string $resource = WaxingItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
