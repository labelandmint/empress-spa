<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Cancel extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $website_address;
    public $phone;
    public $email;

    /**
     * Create a new message instance.
     *
     * @param $user
     * @return void
     */
    public function __construct($name,$website_address, $phone, $email)
    {
        $this->name = $name;
        $this->website_address = $website_address;
        $this->phone = $phone;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.cancel')
        ->subject('Sorry to See You Go, ' . $this->name);
    }
}
