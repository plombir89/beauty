<?php

namespace App\Livewire;

use App\Mail\BookingRequestSubmitted;
use App\Models\BookingRequest;
use App\Models\Service;
use App\Models\Specialist;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Livewire\Component;

class BookingForm extends Component
{
    public ?int $initialServiceId = null;

    public ?int $specialistId = null;

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

    public function updatedSpecialistId(): void
    {
        $this->submitted = false;

        if ($this->specialistId === null || $this->serviceId === null) {
            return;
        }

        if (! $this->serviceBelongsToSpecialist()) {
            $this->serviceId = null;
        }
    }

    public function updatedServiceId(): void
    {
        $this->submitted = false;

        if ($this->specialistId === null || $this->serviceId === null) {
            return;
        }

        if (! $this->serviceBelongsToSpecialist()) {
            $this->specialistId = null;
        }
    }

    public function submit(): void
    {
        if ($this->company !== '') {
            return;
        }

        $validated = $this->validate([
            'specialistId' => ['required', 'integer', Rule::exists('specialists', 'id')->where('is_active', true)],
            'serviceId' => ['required', 'integer', Rule::exists('services', 'id')->where('is_active', true)],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'regex:/^[+\d][\d\s().-]{6,}$/', 'max:50'],
            'message' => ['nullable', 'string', 'max:2000'],
        ], attributes: [
            'specialistId' => __('site.booking.specialist'),
            'serviceId' => __('site.booking.service'),
        ]);

        if (! $this->serviceBelongsToSpecialist()) {
            $this->addError('serviceId', __('site.booking.invalid_service_specialist'));

            return;
        }

        $bookingRequest = BookingRequest::query()->create([
            'specialist_id' => $validated['specialistId'],
            'service_id' => $validated['serviceId'],
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

        $this->reset(['specialistId', 'serviceId', 'name', 'email', 'phone', 'message', 'company']);
        $this->serviceId = $this->initialServiceId;
        $this->submitted = true;
    }

    public function render(): View
    {
        return view('livewire.booking-form', [
            'specialists' => Specialist::query()
                ->active()
                ->when($this->serviceId, fn ($query) => $query->whereHas(
                    'services',
                    fn ($query) => $query->whereKey($this->serviceId),
                ))
                ->ordered()
                ->get(),
            'services' => Service::query()
                ->with('category')
                ->active()
                ->when($this->specialistId, fn ($query) => $query->whereHas(
                    'specialists',
                    fn ($query) => $query->whereKey($this->specialistId),
                ))
                ->ordered()
                ->get(),
        ]);
    }

    protected function serviceBelongsToSpecialist(): bool
    {
        if ($this->serviceId === null || $this->specialistId === null) {
            return false;
        }

        return Service::query()
            ->whereKey($this->serviceId)
            ->whereHas('specialists', fn ($query) => $query->whereKey($this->specialistId))
            ->exists();
    }
}
