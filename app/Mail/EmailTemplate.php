<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailTemplate extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $logo;
    public $graphic;
    public $header;
    public $body;

    /**
     * Create a new message instance.
     *
     * @param $user
     * @return void
     */
    public function __construct($name,$logo,$graphic,$header,$body)
    {
        $this->name = $name;
        $this->logo = $logo;
        $this->graphic = $graphic;
        $this->header = $header;
        $this->body = $body;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.email')
        ->subject('Welcome to Our Service');
    }
}
