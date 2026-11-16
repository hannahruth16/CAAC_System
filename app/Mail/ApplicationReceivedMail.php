<?php

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Registration $registration,
        public string $recommenderName
    ) {}

    /**
     * Email subject.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[CAAC Membership] Application Received – Reference #'
                . $this->registration->registration_no,
        );
    }

    /**
     * Email content.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.application-received',
            with: [
                'registration' => $this->registration,
                'recommenderName' => $this->recommenderName,
                'applicantName' => trim(
                    $this->registration->first_name . ' ' .
                    ($this->registration->middle_name ?? '') . ' ' .
                    $this->registration->family_name . ' ' .
                    ($this->registration->suffix ?? '')
                ),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}