<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AddUser extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $user_role;
    public $website_address;
    public $phone;
    public $email;
    public $resetLink;

    /**
     * Create a new message instance.
     *
     * @param $user
     * @return void
     */
    public function __construct($name, $user_role, $website_address, $phone, $email, $resetLink)
    {
        $this->name = $name;
        $this->user_role = $user_role;
        $this->website_address = $website_address;
        $this->phone = $phone;
        $this->email = $email;
        $this->resetLink = $resetLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.add_user')
            ->subject('Action Required: Set Up Your Empress Spa Account');
    }
}
