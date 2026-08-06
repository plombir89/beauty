<?php

use App\Filament\Resources\Specialists\SpecialistResource;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

test('specialist form uploads images to public specialists storage', function (): void {
    $schema = SpecialistResource::form(Schema::make());
    $upload = collect($schema->getComponents())
        ->first(fn (object $component): bool => $component instanceof FileUpload);

    expect($upload)->toBeInstanceOf(FileUpload::class)
        ->and($upload->getDiskName())->toBe('public')
        ->and($upload->getDirectory())->toBe('specialists');
});
