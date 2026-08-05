<?php

namespace App\Filament\Resources\StudioProfiles\Pages;

use App\Filament\Resources\StudioProfiles\StudioProfileResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditStudioProfile extends EditRecord
{
    protected static string $resource = StudioProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
