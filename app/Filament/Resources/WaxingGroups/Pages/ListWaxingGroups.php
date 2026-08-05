<?php

namespace App\Filament\Resources\WaxingGroups\Pages;

use App\Filament\Resources\WaxingGroups\WaxingGroupResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWaxingGroups extends ListRecords
{
    protected static string $resource = WaxingGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
