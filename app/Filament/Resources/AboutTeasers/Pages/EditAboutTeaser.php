<?php

namespace App\Filament\Resources\AboutTeasers\Pages;

use App\Filament\Resources\AboutTeasers\AboutTeaserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAboutTeaser extends EditRecord
{
    protected static string $resource = AboutTeaserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
