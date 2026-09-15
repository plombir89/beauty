<?php

namespace App\Livewire;

use App\Mail\BookingRequestSubmitted;
use App\Models\BookingRequest;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Livewire\Component;

class BookingForm extends Component
{
    public ?int $initialServiceId = null;

    public ?int $serviceId = null;

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $message = '';

    public string $company = '';

    public bool $submitted = false;

    public function mount(?int $initialServiceId = null): void
    {
        $this->initialServiceId = $initialServiceId;
        $this->serviceId = $initialServiceId;
    }

    public function updatedServiceId(): void
    {
        $this->submitted = false;
    }

    public function submit(): void
    {
        if ($this->company !== '') {
            return;
        }

        $validated = $this->validate([
            'serviceId' => ['nullable', 'integer', Rule::exists('services', 'id')->where('is_active', true)],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'regex:/^[+\d][\d\s().-]{6,}$/', 'max:50'],
            'message' => ['nullable', 'string', 'max:2000'],
        ], attributes: [
            'serviceId' => __('site.booking.service'),
            'name' => __('site.booking.name'),
            'email' => __('site.booking.email'),
            'phone' => __('site.booking.phone'),
            'message' => __('site.booking.message'),
        ]);

        $bookingRequest = BookingRequest::query()->create([
            'specialist_id' => null,
            'service_id' => $validated['serviceId'] ?? null,
            'locale' => app()->getLocale(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'message' => $validated['message'] ?? null,
            'source_url' => request()->headers->get('referer') ?: url()->current(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $recipient = config('services.studio.booking_email');

        if (is_string($recipient) && $recipient !== '') {
            Mail::to($recipient)->send(new BookingRequestSubmitted($bookingRequest));
        }

        $this->reset(['serviceId', 'name', 'email', 'phone', 'message', 'company']);
        $this->serviceId = $this->initialServiceId;
        $this->submitted = true;
    }

    public function render(): View
    {
        return view('livewire.booking-form', [
            'services' => Service::query()
                ->with('category')
                ->active()
                ->ordered()
                ->get(),
        ]);
    }
}
