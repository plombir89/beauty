<?php

namespace App\Filament\Resources\AboutPageContents\Pages;

use App\Filament\Resources\AboutPageContents\AboutPageContentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAboutPageContents extends ListRecords
{
    protected static string $resource = AboutPageContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
