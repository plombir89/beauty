<?php

namespace App\Filament\Resources\ContactPolicies\Pages;

use App\Filament\Resources\ContactPolicies\ContactPolicyResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditContactPolicy extends EditRecord
{
    protected static string $resource = ContactPolicyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
