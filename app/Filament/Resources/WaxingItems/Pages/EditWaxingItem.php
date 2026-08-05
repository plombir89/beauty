<?php

namespace App\Filament\Resources\WaxingItems\Pages;

use App\Filament\Resources\WaxingItems\WaxingItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWaxingItem extends EditRecord
{
    protected static string $resource = WaxingItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
