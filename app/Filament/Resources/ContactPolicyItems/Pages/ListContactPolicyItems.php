<?php

namespace App\Filament\Resources\ContactPolicyItems\Pages;

use App\Filament\Resources\ContactPolicyItems\ContactPolicyItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListContactPolicyItems extends ListRecords
{
    protected static string $resource = ContactPolicyItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
