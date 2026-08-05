<?php

namespace App\Filament\Resources\DepositSettings\Pages;

use App\Filament\Resources\DepositSettings\DepositSettingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDepositSetting extends EditRecord
{
    protected static string $resource = DepositSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
