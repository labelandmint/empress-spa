<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Booking extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $logo;
    public $service;
    public $booking_date;
    public $time;
    public $website_address;
    public $phone;
    public $email;
    public $location;

    /**
     * Create a new message instance.
     *
     * @param $user
     * @return void
     */
    public function __construct($name,$logo,$service,$booking_date,$time,$website_address, $phone, $email, $location)
    {
        $this->name = $name;
        $this->logo = $logo;
        $this->service = $service;
        $this->booking_date = $booking_date;
        $this->time = $time;
        $this->website_address = $website_address;
        $this->phone = $phone;
        $this->email = $email;
        $this->location = $location;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.booking')
        ->subject('Your Empress Spa Booking is Confirmed!');
    }
}
