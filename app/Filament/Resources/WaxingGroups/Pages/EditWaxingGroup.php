<?php

namespace App\Filament\Resources\WaxingGroups\Pages;

use App\Filament\Resources\WaxingGroups\WaxingGroupResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWaxingGroup extends EditRecord
{
    protected static string $resource = WaxingGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
