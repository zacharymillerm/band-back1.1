<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $email = $this->from(config('mail.from.address'))
                      ->subject('New Contact Form Submission')
                      ->view('emails.contact')
                      ->with('data', $this->data);

        // Attach the file if it exists
        if (isset($this->data['file_path'])) {
            $email->attach($this->data['file_path'], [
                'as' => $this->data['file_name'],  // Original file name
                'mime' => mime_content_type($this->data['file_path']),
            ]);
        }

        return $email;
    }
}
