<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactUs extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->data['type'] . ' from ' . $this->data['name'])
                    ->to(config('mail.from.address'))
                    ->from($this->data['email'])
                    ->replyTo($this->data['email'], $this->data['name'])
                    ->markdown('emails.contactus')
                    ->with([
                        'name' => $this->data['name'],
                        'email' => $this->data['email'],
                        'phone' => $this->data['phone'],
                        'type' => $this->data['type'],
                        'body' => $this->data['body'],
                    ]);
    }
}
