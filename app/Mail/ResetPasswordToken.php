<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordToken extends Mailable
{
    use Queueable, SerializesModels;

    private $link;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($resetPasswordLink)
    {
        $this->link = $resetPasswordLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.resetPasswordToken', [
            'link' => $this->link
        ]);
    }
}
