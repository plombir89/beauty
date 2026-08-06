<?php

use App\Filament\Resources\HomeHeroBlocks\HomeHeroBlockResource;
use App\Models\HomeHeroBlock;

test('home hero block resource is a single editable record resource', function (): void {
    $pages = HomeHeroBlockResource::getPages();

    expect(HomeHeroBlockResource::canCreate())->toBeFalse()
        ->and(HomeHeroBlockResource::canDelete(new HomeHeroBlock))->toBeFalse()
        ->and(HomeHeroBlockResource::canDeleteAny())->toBeFalse()
        ->and($pages)->toHaveKeys(['index', 'edit'])
        ->and($pages)->not->toHaveKey('create');
});
