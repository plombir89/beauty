<?php

use App\Filament\Resources\Services\ServiceResource;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Schema;
use Tests\TestCase;

uses(TestCase::class);

test('service rich editor stores attachments on public services storage', function (): void {
    $schema = ServiceResource::form(Schema::make());
    $editor = collect(serviceResourceTestFlattenComponents($schema->getComponents()))
        ->first(fn (object $component): bool => $component instanceof RichEditor);

    expect($editor)->toBeInstanceOf(RichEditor::class)
        ->and($editor->getFileAttachmentsDiskName())->toBe('public')
        ->and($editor->getFileAttachmentsDirectory())->toBe('services/content');
});

test('service image uploads use public services storage', function (): void {
    $schema = ServiceResource::form(Schema::make());
    $uploads = collect(serviceResourceTestFlattenComponents($schema->getComponents()))
        ->filter(fn (object $component): bool => $component instanceof FileUpload)
        ->keyBy(fn (FileUpload $upload): string => $upload->getName());

    $servicesPageImage = $uploads->get('image');
    $detailImage = $uploads->get('detail_image');

    expect($servicesPageImage)->toBeInstanceOf(FileUpload::class)
        ->and($servicesPageImage->getDiskName())->toBe('public')
        ->and($servicesPageImage->getDirectory())->toBe('services')
        ->and($servicesPageImage->getVisibility())->toBe('public')
        ->and($detailImage)->toBeInstanceOf(FileUpload::class)
        ->and($detailImage->getDiskName())->toBe('public')
        ->and($detailImage->getDirectory())->toBe('services')
        ->and($detailImage->getVisibility())->toBe('public');
});

/**
 * @param  array<int, object>  $components
 * @return array<int, object>
 */
function serviceResourceTestFlattenComponents(array $components): array
{
    $flat = [];

    foreach ($components as $component) {
        $flat[] = $component;

        $flat = array_merge($flat, serviceResourceTestFlattenComponents(serviceResourceTestChildComponents($component)));
    }

    return $flat;
}

/**
 * @return array<int, object>
 */
function serviceResourceTestChildComponents(object $component): array
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
