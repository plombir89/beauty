<?php

use App\Filament\Resources\ContactPolicies\ContactPolicyResource;
use App\Filament\Resources\ContactPolicyItems\ContactPolicyItemResource;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Schema;
use Tests\TestCase;

uses(TestCase::class);

test('contact policy intro and item text fields use rich editor with public contact attachments', function (): void {
    $schema = ContactPolicyResource::form(Schema::make());
    $editors = collect(contactPolicyResourceTestFlattenComponents($schema->getComponents()))
        ->filter(fn (object $component): bool => $component instanceof RichEditor)
        ->values();

    expect($editors->map(fn (RichEditor $editor): ?string => contactPolicyResourceTestStatePath($editor))->all())
        ->toContain('intro.en', 'intro.ru', 'text.en', 'text.ru');

    $editors->each(function (RichEditor $editor): void {
        expect($editor->getFileAttachmentsDiskName())->toBe('public')
            ->and($editor->getFileAttachmentsDirectory())->toBe('contact/content');
    });
});

test('contact policy item text fields use rich editor with public contact attachments', function (): void {
    $schema = ContactPolicyItemResource::form(Schema::make());
    $editors = collect(contactPolicyResourceTestFlattenComponents($schema->getComponents()))
        ->filter(fn (object $component): bool => $component instanceof RichEditor)
        ->values();

    expect($editors->map(fn (RichEditor $editor): ?string => contactPolicyResourceTestStatePath($editor))->all())
        ->toContain('text.en', 'text.ru');

    $editors->each(function (RichEditor $editor): void {
        expect($editor->getFileAttachmentsDiskName())->toBe('public')
            ->and($editor->getFileAttachmentsDirectory())->toBe('contact/content');
    });
});

/**
 * @param  array<int, object>  $components
 * @return array<int, object>
 */
function contactPolicyResourceTestFlattenComponents(array $components): array
{
    $flat = [];

    foreach ($components as $component) {
        $flat[] = $component;

        $flat = array_merge($flat, contactPolicyResourceTestFlattenComponents(contactPolicyResourceTestChildComponents($component)));
    }

    return $flat;
}

/**
 * @return array<int, object>
 */
function contactPolicyResourceTestChildComponents(object $component): array
{
    $class = new ReflectionClass($component);

    do {
        if ($class->hasProperty('childComponents')) {
            $property = $class->getProperty('childComponents');
            $property->setAccessible(true);
            $children = [];

            foreach ((array) $property->getValue($component) as $componentGroup) {
                foreach (is_array($componentGroup) ? $componentGroup : [$componentGroup] as $child) {
                    if (is_object($child)) {
                        $children[] = $child;
                    }
                }
            }

            return $children;
        }

        $class = $class->getParentClass();
    } while ($class instanceof ReflectionClass);

    return [];
}

function contactPolicyResourceTestStatePath(object $component): ?string
{
    $class = new ReflectionClass($component);

    do {
        if ($class->hasProperty('statePath')) {
            $property = $class->getProperty('statePath');
            $property->setAccessible(true);
            $statePath = $property->getValue($component);

            return is_string($statePath) ? $statePath : null;
        }

        $class = $class->getParentClass();
    } while ($class instanceof ReflectionClass);

    return null;
}
