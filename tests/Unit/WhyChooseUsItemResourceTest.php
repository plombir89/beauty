<?php

use App\Filament\Resources\WhyChooseUsItems\Pages\EditWhyChooseUsItem;
use App\Filament\Resources\WhyChooseUsItems\WhyChooseUsItemResource;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Tests\TestCase;

uses(TestCase::class);

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

test('why choose body fields use rich editor with public choose attachments', function (): void {
    $schema = WhyChooseUsItemResource::form(Schema::make());
    $editors = collect(whyChooseUsItemResourceTestFlattenComponents($schema->getComponents()))
        ->filter(fn (object $component): bool => $component instanceof RichEditor)
        ->values();

    expect($editors->map(fn (RichEditor $editor): ?string => componentStatePath($editor))->all())
        ->toContain('body.en', 'body.ru');

    $editors->each(function (RichEditor $editor): void {
        expect($editor->getFileAttachmentsDiskName())->toBe('public')
            ->and($editor->getFileAttachmentsDirectory())->toBe('choose/content');
    });
});

test('why choose edit page converts legacy body paragraphs before filling rich editor', function (): void {
    $data = whyChooseUsItemResourceTestMutateFormDataBeforeFill([
        'body' => [
            'en' => ['First paragraph', 'Second <unsafe> paragraph'],
            'ru' => '<p>Готовый rich text</p>',
        ],
    ]);

    expect($data['body']['en'])->toBe('<p>First paragraph</p><p>Second &lt;unsafe&gt; paragraph</p>')
        ->and($data['body']['ru'])->toBe('<p>Готовый rich text</p>');
});

/**
 * @param  array<string, mixed>  $data
 * @return array<string, mixed>
 */
function whyChooseUsItemResourceTestMutateFormDataBeforeFill(array $data): array
{
    $page = new EditWhyChooseUsItem;
    $method = new ReflectionMethod($page, 'mutateFormDataBeforeFill');
    $method->setAccessible(true);

    return $method->invoke($page, $data);
}

/**
 * @param  array<int, object>  $components
 * @return array<int, object>
 */
function whyChooseUsItemResourceTestFlattenComponents(array $components): array
{
    $flat = [];

    foreach ($components as $component) {
        $flat[] = $component;
        $flat = array_merge($flat, whyChooseUsItemResourceTestFlattenComponents(defaultChildComponents($component)));
    }

    return $flat;
}

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
