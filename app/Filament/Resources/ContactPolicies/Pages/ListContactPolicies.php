<?php

namespace App\Filament\Resources\ContactPolicies\Pages;

use App\Filament\Resources\ContactPolicies\ContactPolicyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListContactPolicies extends ListRecords
{
    protected static string $resource = ContactPolicyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
