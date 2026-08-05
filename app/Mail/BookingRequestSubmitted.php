<?php

namespace App\Mail;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingRequestSubmitted extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public BookingRequest $bookingRequest)
    {
        $this->afterCommit();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New booking request - Elegant Beauty Studio',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.booking-request-submitted',
            with: [
                'bookingRequest' => $this->bookingRequest->loadMissing(['service', 'specialist']),
            ],
        );
    }
}
