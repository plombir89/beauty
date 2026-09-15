<x-mail::message>
# New booking request

**Client:** {{ $bookingRequest->name }}

**Phone:** {{ $bookingRequest->phone }}

**Email:** {{ $bookingRequest->email }}

**Specialist:** {{ $bookingRequest->specialist?->name ?? 'Not assigned' }}

**Service:** {{ $bookingRequest->service?->title ?? 'Not selected' }}

**Locale:** {{ strtoupper($bookingRequest->locale) }}

@if ($bookingRequest->message)
**Message:**

{{ $bookingRequest->message }}
@endif

**Source:** {{ $bookingRequest->source_url }}
</x-mail::message>
