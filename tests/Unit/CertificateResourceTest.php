<?php

use App\Filament\Resources\Certificates\CertificateResource;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

test('certificate form keeps image compact in a three column layout', function (): void {
    $schema = CertificateResource::form(Schema::make());
    $upload = $schema->getComponents()[2];

    expect($schema->getColumns())->toBe(['lg' => 3])
        ->and($upload)->toBeInstanceOf(FileUpload::class)
        ->and($upload->getColumnSpan())->toBe([
            'default' => 1,
            'sm' => null,
            'md' => null,
            'lg' => null,
            'xl' => null,
            '2xl' => null,
        ])
        ->and($upload->getDiskName())->toBe('public')
        ->and($upload->getDirectory())->toBe('certificates');
});
