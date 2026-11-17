<?php

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationReturnedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Registration $registration,
        public string $applicantName,
        public string $resubmitUrl
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[CAAC Membership] Application Returned for Correction – Reference #'
                . $this->registration->registration_no
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.application-returned',
            with: [
                'registration' => $this->registration,
                'applicantName' => $this->applicantName,
                'resubmitUrl' => $this->resubmitUrl,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}