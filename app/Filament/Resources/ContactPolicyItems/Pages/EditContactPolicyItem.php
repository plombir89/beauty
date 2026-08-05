<?php

namespace App\Filament\Resources\ContactPolicyItems\Pages;

use App\Filament\Resources\ContactPolicyItems\ContactPolicyItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditContactPolicyItem extends EditRecord
{
    protected static string $resource = ContactPolicyItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
