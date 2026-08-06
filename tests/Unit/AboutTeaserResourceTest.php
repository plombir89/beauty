<?php

use App\Filament\Resources\AboutTeasers\AboutTeaserResource;
use App\Models\AboutTeaser;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

test('about teaser form uploads images to public about storage', function (): void {
    $schema = AboutTeaserResource::form(Schema::make());
    $upload = collect($schema->getComponents())
        ->first(fn (object $component): bool => $component instanceof FileUpload);

    expect($upload)->toBeInstanceOf(FileUpload::class)
        ->and($upload->getDiskName())->toBe('public')
        ->and($upload->getDirectory())->toBe('about');
});

test('about teaser resource is a single editable record resource', function (): void {
    $pages = AboutTeaserResource::getPages();

    expect(AboutTeaserResource::canCreate())->toBeFalse()
        ->and(AboutTeaserResource::canDelete(new AboutTeaser))->toBeFalse()
        ->and(AboutTeaserResource::canDeleteAny())->toBeFalse()
        ->and($pages)->toHaveKeys(['index', 'edit'])
        ->and($pages)->not->toHaveKey('create');
});
