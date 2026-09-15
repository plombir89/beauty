<?php

use App\Filament\Resources\BookingRequests\BookingRequestResource;
use App\Models\BookingRequest;

test('booking requests navigation badge shows pending requests in danger color', function (): void {
    BookingRequest::query()->create([
        'locale' => 'en',
        'name' => 'Pending Client',
        'email' => 'pending@example.com',
        'phone' => '+37360000001',
        'status' => BookingRequest::StatusPending,
    ]);

    BookingRequest::query()->create([
        'locale' => 'en',
        'name' => 'Second Pending Client',
        'email' => 'second-pending@example.com',
        'phone' => '+37360000002',
        'status' => BookingRequest::StatusPending,
    ]);

    BookingRequest::query()->create([
        'locale' => 'en',
        'name' => 'Confirmed Client',
        'email' => 'confirmed@example.com',
        'phone' => '+37360000003',
        'status' => 'confirmed',
    ]);

    expect(BookingRequestResource::getNavigationBadge())->toBe('2')
        ->and(BookingRequestResource::getNavigationBadgeColor())->toBe('danger');
});
