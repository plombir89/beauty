<?php

use App\Livewire\BookingForm;
use App\Mail\BookingRequestSubmitted;
use App\Models\BookingRequest;
use App\Models\Service;
use Database\Seeders\ElegantBeautySeeder;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->seed(ElegantBeautySeeder::class);
});

test('booking form shows optional service and hides specialist', function (): void {
    Livewire::test(BookingForm::class)
        ->assertSee(__('site.booking.service'))
        ->assertSee(__('site.booking.select_service'))
        ->assertDontSee(__('site.booking.specialist'))
        ->assertDontSee('booking-specialist');
});

test('booking form validation messages are localized in russian', function (): void {
    app()->setLocale('ru');

    Livewire::test(BookingForm::class)
        ->call('submit')
        ->assertHasErrors(['name', 'phone', 'email'])
        ->assertHasNoErrors(['serviceId'])
        ->assertSee('Поле «Имя» обязательно.')
        ->assertSee('Поле «Телефон» обязательно.')
        ->assertSee('Поле «Email» обязательно.')
        ->assertDontSee('The Услуга field is required.')
        ->assertDontSee('The name field is required.');
});

test('booking form stores request and queues email', function (): void {
    Mail::fake();

    $service = Service::query()->active()->firstOrFail();

    Livewire::test(BookingForm::class)
        ->set('serviceId', $service->id)
        ->set('name', 'Jane Client')
        ->set('email', 'jane@example.com')
        ->set('phone', '+12535550123')
        ->set('message', 'Friday afternoon if possible')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertSet('submitted', true);

    $bookingRequest = BookingRequest::query()->firstOrFail();

    expect($bookingRequest->specialist_id)->toBeNull()
        ->and($bookingRequest->service_id)->toBe($service->id)
        ->and($bookingRequest->status)->toBe(BookingRequest::StatusPending);

    Mail::assertQueued(BookingRequestSubmitted::class, fn (BookingRequestSubmitted $mail): bool => $mail->bookingRequest->is($bookingRequest));
});

test('booking form stores request without service', function (): void {
    Mail::fake();

    Livewire::test(BookingForm::class)
        ->set('name', 'Jane Client')
        ->set('email', 'jane@example.com')
        ->set('phone', '+12535550123')
        ->call('submit')
        ->assertHasNoErrors()
        ->assertSet('submitted', true);

    $bookingRequest = BookingRequest::query()->firstOrFail();

    expect($bookingRequest->specialist_id)->toBeNull()
        ->and($bookingRequest->service_id)->toBeNull()
        ->and($bookingRequest->status)->toBe(BookingRequest::StatusPending);

    Mail::assertQueued(BookingRequestSubmitted::class, fn (BookingRequestSubmitted $mail): bool => $mail->bookingRequest->is($bookingRequest));
});

test('booking form rejects an inactive selected service', function (): void {
    Mail::fake();

    $service = Service::query()->active()->firstOrFail();
    $service->update(['is_active' => false]);

    Livewire::test(BookingForm::class)
        ->set('serviceId', $service->id)
        ->set('name', 'Jane Client')
        ->set('email', 'jane@example.com')
        ->set('phone', '+12535550123')
        ->call('submit')
        ->assertHasErrors(['serviceId' => 'exists']);

    expect(BookingRequest::query()->count())->toBe(0);
    Mail::assertNothingQueued();
});
