<?php

use App\Filament\Resources\AboutPageContents\AboutPageContentResource;
use App\Models\AboutPageContent;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Schema;
use Tests\TestCase;

uses(TestCase::class);

test('about page content form uploads images to public about storage', function (): void {
    $schema = AboutPageContentResource::form(Schema::make());
    $upload = collect($schema->getComponents())
        ->first(fn (object $component): bool => $component instanceof FileUpload);

    expect($upload)->toBeInstanceOf(FileUpload::class)
        ->and($upload->getDiskName())->toBe('public')
        ->and($upload->getDirectory())->toBe('about');
});

test('about page content text fields use rich editor with public about attachments', function (): void {
    $schema = AboutPageContentResource::form(Schema::make());
    $editors = collect(aboutPageContentResourceTestFlattenComponents($schema->getComponents()))
        ->filter(fn (object $component): bool => $component instanceof RichEditor)
        ->values();

    expect($editors->map(fn (RichEditor $editor): ?string => aboutPageContentResourceTestStatePath($editor))->all())
        ->toContain('text.en', 'text.ru', 'text2.en', 'text2.ru');

    $editors->each(function (RichEditor $editor): void {
        expect($editor->getFileAttachmentsDiskName())->toBe('public')
            ->and($editor->getFileAttachmentsDirectory())->toBe('about/content');
    });
});

test('about page content resource is a single editable record resource', function (): void {
    $pages = AboutPageContentResource::getPages();

    expect(AboutPageContentResource::canCreate())->toBeFalse()
        ->and(AboutPageContentResource::canDelete(new AboutPageContent))->toBeFalse()
        ->and(AboutPageContentResource::canDeleteAny())->toBeFalse()
        ->and($pages)->toHaveKeys(['index', 'edit'])
        ->and($pages)->not->toHaveKey('create');
});

/**
 * @param  array<int, object>  $components
 * @return array<int, object>
 */
function aboutPageContentResourceTestFlattenComponents(array $components): array
{
    $flat = [];

    foreach ($components as $component) {
        $flat[] = $component;

        $flat = array_merge($flat, aboutPageContentResourceTestFlattenComponents(aboutPageContentResourceTestChildComponents($component)));
    }

    return $flat;
}

/**
 * @return array<int, object>
 */
function aboutPageContentResourceTestChildComponents(object $component): array
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

function aboutPageContentResourceTestStatePath(object $component): ?string
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
