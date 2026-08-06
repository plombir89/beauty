<?php

use App\Filament\Resources\Pages\PageResource;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

test('page resource uploads seo images to public seo storage', function (): void {
    $schema = PageResource::form(Schema::make());
    $upload = collect($schema->getComponents())
        ->first(fn (object $component): bool => $component instanceof FileUpload);

    expect($upload)->toBeInstanceOf(FileUpload::class)
        ->and($upload->getDiskName())->toBe('public')
        ->and($upload->getDirectory())->toBe('seo');
});
