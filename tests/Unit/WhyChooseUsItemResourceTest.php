<?php

use App\Filament\Resources\WhyChooseUsItems\WhyChooseUsItemResource;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

test('why choose title fields generate locale slugs and appear before slug fields', function (): void {
    $schema = WhyChooseUsItemResource::form(Schema::make());
    $tabs = collect($schema->getComponents())
        ->first(fn (object $component): bool => $component instanceof Tabs);

    expect($tabs)->toBeInstanceOf(Tabs::class);

    /** @var array<int, Tab> $localeTabs */
    $localeTabs = defaultChildComponents($tabs);

    expect(array_slice(localeFieldStatePaths($localeTabs[0]), 0, 2))->toBe(['title.en', 'slug.en'])
        ->and(array_slice(localeFieldStatePaths($localeTabs[1]), 0, 2))->toBe(['title.ru', 'slug.ru'])
        ->and(titleFieldHasSlugGeneration($localeTabs[0]))->toBeTrue()
        ->and(titleFieldHasSlugGeneration($localeTabs[1]))->toBeTrue();
});

/**
 * @return array<int, object>
 */
function defaultChildComponents(object $component): array
{
    $property = new ReflectionProperty($component, 'childComponents');
    $property->setAccessible(true);

    $childComponents = $property->getValue($component);

    return is_array($childComponents) && isset($childComponents['default']) && is_array($childComponents['default'])
        ? $childComponents['default']
        : [];
}

/**
 * @return array<int, string|null>
 */
function localeFieldStatePaths(Tab $tab): array
{
    return array_map(
        fn (object $field): ?string => componentStatePath($field),
        defaultChildComponents($tab),
    );
}

function titleFieldHasSlugGeneration(Tab $tab): bool
{
    $titleField = defaultChildComponents($tab)[0] ?? null;

    if (! $titleField instanceof TextInput || ! $titleField->isLiveOnBlur()) {
        return false;
    }

    $property = new ReflectionProperty($titleField, 'afterStateUpdated');
    $property->setAccessible(true);

    return count((array) $property->getValue($titleField)) > 0;
}

function componentStatePath(object $component): ?string
{
    $property = new ReflectionProperty($component, 'statePath');
    $property->setAccessible(true);

    $statePath = $property->getValue($component);

    return is_string($statePath) ? $statePath : null;
}
