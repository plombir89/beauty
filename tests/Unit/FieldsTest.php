<?php

use App\Filament\Support\Fields;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Tests\TestCase;

uses(TestCase::class);

test('shared image upload and column fields use public storage', function (): void {
    $upload = Fields::imageUpload('image', 'blog');
    $column = Fields::imageColumn();

    expect($upload)->toBeInstanceOf(FileUpload::class)
        ->and($upload->getDiskName())->toBe('public')
        ->and($upload->getDirectory())->toBe('blog')
        ->and($column->getDiskName())->toBe('public');
});

test('shared rich editor stores attachments on public storage', function (): void {
    $tabs = Fields::translations([
        ['name' => 'body', 'label' => 'Body', 'type' => 'rich-editor', 'fileAttachmentsDirectory' => 'blog/content'],
    ]);

    $editor = collect(fieldsTestFlattenComponents([$tabs]))
        ->first(fn (object $component): bool => $component instanceof RichEditor);

    expect($editor)->toBeInstanceOf(RichEditor::class)
        ->and($editor->getFileAttachmentsDiskName())->toBe('public')
        ->and($editor->getFileAttachmentsDirectory())->toBe('blog/content');
});

/**
 * @param  array<int, object>  $components
 * @return array<int, object>
 */
function fieldsTestFlattenComponents(array $components): array
{
    $flat = [];

    foreach ($components as $component) {
        $flat[] = $component;

        $flat = array_merge($flat, fieldsTestFlattenComponents(fieldsTestChildComponents($component)));
    }

    return $flat;
}

/**
 * @return array<int, object>
 */
function fieldsTestChildComponents(object $component): array
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
