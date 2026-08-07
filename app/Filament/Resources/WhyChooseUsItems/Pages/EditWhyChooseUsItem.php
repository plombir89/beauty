<?php

namespace App\Filament\Resources\WhyChooseUsItems\Pages;

use App\Filament\Resources\WhyChooseUsItems\WhyChooseUsItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWhyChooseUsItem extends EditRecord
{
    protected static string $resource = WhyChooseUsItemResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (isset($data['body']) && is_array($data['body'])) {
            $data['body'] = array_map(
                fn (mixed $translation): mixed => $this->normalizeLegacyBodyTranslation($translation),
                $data['body'],
            );
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    private function normalizeLegacyBodyTranslation(mixed $translation): mixed
    {
        if (! is_array($translation) || ! array_is_list($translation)) {
            return $translation;
        }

        return collect($translation)
            ->map(fn (mixed $paragraph): string => trim((string) $paragraph))
            ->filter()
            ->map(fn (string $paragraph): string => '<p>'.e($paragraph).'</p>')
            ->implode('');
    }
}
