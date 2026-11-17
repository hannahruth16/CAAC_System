<?php

namespace App\Mail;

use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $applicant_name;

    public function __construct(Registration $registration, $applicantName)
    {
        $this->applicant_name = $applicantName;
    }

    public function build()
    {
        return $this->subject(
            '[CAAC Membership] Congratulations! Your Application is Approved – Next Steps for Training'
        )
        ->view('emails.registration-approved');
    }
}