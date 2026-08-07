<?php

use App\Livewire\BookingForm;
use App\Mail\BookingRequestSubmitted;
use App\Models\BookingRequest;
use App\Models\Specialist;
use Database\Seeders\ElegantBeautySeeder;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->seed(ElegantBeautySeeder::class);
});

test('booking form shows service before specialist', function (): void {
    Livewire::test(BookingForm::class)
        ->assertSeeInOrder([
            __('site.booking.service'),
            __('site.booking.specialist'),
        ]);
});

test('booking form stores request and queues email', function (): void {
    Mail::fake();

    $specialist = Specialist::query()->with('services')->whereHas('services')->firstOrFail();
    $service = $specialist->services->first();

    Livewire::test(BookingForm::class)
        ->set('serviceId', $service->id)
        ->set('specialistId', $specialist->id)
        ->set('name', 'Jane Client')
        ->set('email', 'jane@example.com')
        ->set('phone', '+12535550123')
        ->set('message', 'Friday afternoon if possible')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertSet('submitted', true);

    $bookingRequest = BookingRequest::query()->firstOrFail();

    expect($bookingRequest->specialist_id)->toBe($specialist->id)
        ->and($bookingRequest->service_id)->toBe($service->id)
        ->and($bookingRequest->status)->toBe(BookingRequest::StatusPending);

    Mail::assertQueued(BookingRequestSubmitted::class, fn (BookingRequestSubmitted $mail): bool => $mail->bookingRequest->is($bookingRequest));
});

test('booking form rejects a service outside the selected specialist', function (): void {
    Mail::fake();

    $specialist = Specialist::query()->where('slug->en', 'vladimir-chernov')->firstOrFail();
    $service = Specialist::query()
        ->where('slug->en', 'studio-esthetician')
        ->firstOrFail()
        ->services()
        ->whereDoesntHave('specialists', fn ($query) => $query->whereKey($specialist->id))
        ->firstOrFail();

    Livewire::test(BookingForm::class)
        ->set('specialistId', $specialist->id)
        ->set('serviceId', $service->id)
        ->set('name', 'Jane Client')
        ->set('email', 'jane@example.com')
        ->set('phone', '+12535550123')
        ->call('submit');

    expect(BookingRequest::query()->count())->toBe(0);
    Mail::assertNothingQueued();
});
