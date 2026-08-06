<?php

namespace App\Filament\Resources\CtaBlocks\Pages;

use App\Filament\Resources\CtaBlocks\CtaBlockResource;
use Filament\Resources\Pages\EditRecord;

class EditCtaBlock extends EditRecord
{
    protected static string $resource = CtaBlockResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        return CtaBlockResource::normalizeFormData($data);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
