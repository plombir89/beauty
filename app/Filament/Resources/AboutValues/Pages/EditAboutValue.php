<?php

namespace App\Filament\Resources\AboutValues\Pages;

use App\Filament\Resources\AboutValues\AboutValueResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAboutValue extends EditRecord
{
    protected static string $resource = AboutValueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
