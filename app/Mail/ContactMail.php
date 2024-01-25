<?php

namespace App\Mail;

use App\Models\ContactRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;
    public ContactRequest $contactRequest;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ContactRequest $contactRequest)
    {
        $this->contactRequest = $contactRequest;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): Mailable
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Contact Request: '. $this->contactRequest->subject)
            ->view('emails.contact');
    }

}
