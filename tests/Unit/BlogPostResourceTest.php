<?php

use App\Filament\Resources\BlogPosts\BlogPostResource;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Schema;
use Tests\TestCase;

uses(TestCase::class);

test('blog post form stores images and editor attachments on public blog storage', function (): void {
    $schema = BlogPostResource::form(Schema::make());
    $components = blogPostResourceTestFlattenComponents($schema->getComponents());

    $upload = collect($components)
        ->first(fn (object $component): bool => $component instanceof FileUpload);
    $editor = collect($components)
        ->first(fn (object $component): bool => $component instanceof RichEditor);

    expect($upload)->toBeInstanceOf(FileUpload::class)
        ->and($upload->getDiskName())->toBe('public')
        ->and($upload->getDirectory())->toBe('blog')
        ->and($editor)->toBeInstanceOf(RichEditor::class)
        ->and($editor->getFileAttachmentsDiskName())->toBe('public')
        ->and($editor->getFileAttachmentsDirectory())->toBe('blog/content');
});

/**
 * @param  array<int, object>  $components
 * @return array<int, object>
 */
function blogPostResourceTestFlattenComponents(array $components): array
{
    $flat = [];

    foreach ($components as $component) {
        $flat[] = $component;

        $flat = array_merge($flat, blogPostResourceTestFlattenComponents(blogPostResourceTestChildComponents($component)));
    }

    return $flat;
}

/**
 * @return array<int, object>
 */
function blogPostResourceTestChildComponents(object $component): array
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
