<?php

namespace App\Filament\Resources\DepositSettings\Pages;

use App\Filament\Resources\DepositSettings\DepositSettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDepositSettings extends ListRecords
{
    protected static string $resource = DepositSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
