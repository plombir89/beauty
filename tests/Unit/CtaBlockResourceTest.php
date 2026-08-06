<?php

use App\Filament\Resources\CtaBlocks\CtaBlockResource;
use App\Models\CtaBlock;

test('cta block resource is a single editable record resource', function (): void {
    $pages = CtaBlockResource::getPages();

    expect(CtaBlockResource::canCreate())->toBeFalse()
        ->and(CtaBlockResource::canDelete(new CtaBlock))->toBeFalse()
        ->and(CtaBlockResource::canDeleteAny())->toBeFalse()
        ->and($pages)->toHaveKeys(['index', 'edit'])
        ->and($pages)->not->toHaveKey('create');
});
