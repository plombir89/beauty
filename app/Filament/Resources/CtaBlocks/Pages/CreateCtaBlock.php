<?php

namespace App\Filament\Resources\CtaBlocks\Pages;

use App\Filament\Resources\CtaBlocks\CtaBlockResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCtaBlock extends CreateRecord
{
    protected static string $resource = CtaBlockResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return CtaBlockResource::normalizeFormData($data);
    }
}
