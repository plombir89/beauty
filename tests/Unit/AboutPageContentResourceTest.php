<?php

use App\Filament\Resources\AboutPageContents\AboutPageContentResource;
use App\Models\AboutPageContent;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

test('about page content form uploads images to public about storage', function (): void {
    $schema = AboutPageContentResource::form(Schema::make());
    $upload = collect($schema->getComponents())
        ->first(fn (object $component): bool => $component instanceof FileUpload);

    expect($upload)->toBeInstanceOf(FileUpload::class)
        ->and($upload->getDiskName())->toBe('public')
        ->and($upload->getDirectory())->toBe('about');
});

test('about page content resource is a single editable record resource', function (): void {
    $pages = AboutPageContentResource::getPages();

    expect(AboutPageContentResource::canCreate())->toBeFalse()
        ->and(AboutPageContentResource::canDelete(new AboutPageContent))->toBeFalse()
        ->and(AboutPageContentResource::canDeleteAny())->toBeFalse()
        ->and($pages)->toHaveKeys(['index', 'edit'])
        ->and($pages)->not->toHaveKey('create');
});
