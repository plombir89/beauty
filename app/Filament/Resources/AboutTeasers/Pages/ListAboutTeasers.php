<?php

namespace App\Filament\Resources\AboutTeasers\Pages;

use App\Filament\Resources\AboutTeasers\AboutTeaserResource;
use Filament\Resources\Pages\ListRecords;

class ListAboutTeasers extends ListRecords
{
    protected static string $resource = AboutTeaserResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
