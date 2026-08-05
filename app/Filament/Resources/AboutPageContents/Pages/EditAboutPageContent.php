<?php

namespace App\Filament\Resources\AboutPageContents\Pages;

use App\Filament\Resources\AboutPageContents\AboutPageContentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAboutPageContent extends EditRecord
{
    protected static string $resource = AboutPageContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
