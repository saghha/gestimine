<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmergencyCallReceived extends Mailable
{
    use Queueable, SerializesModels;

    public $distressCall;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data_mail)
    {
        $this->distressCall = $data_mail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.emergency_call')->with($this->distressCall);
    }
}
