<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $admin;
    public $password;

    public function __construct($admin, $password)
    {
        $this->admin = $admin;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Your Admin Account Password')
                    ->view('emails.admin_password')
                    ->with([
                        'name' => $this->admin->name,
                        'password' => $this->password,
                    ]);
    }
}
