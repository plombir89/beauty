<?php

namespace App\Filament\Resources\CtaBlocks\Pages;

use App\Filament\Resources\CtaBlocks\CtaBlockResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCtaBlocks extends ListRecords
{
    protected static string $resource = CtaBlockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
