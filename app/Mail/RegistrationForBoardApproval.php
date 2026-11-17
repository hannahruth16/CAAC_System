<?php

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationForBoardApproval extends Mailable
{
    use Queueable, SerializesModels;

    public $registration;
    public $applicantName;

    public function __construct(
        Registration $registration,
        $applicantName) 
    {
        $this->registration = $registration;
        $this->applicantName = $applicantName;
    }

    public function build()
    {
        return $this->subject(
            'Membership Application – For Board Approval'
        )
        ->view('emails.registration-for-board-approval');
    }
}