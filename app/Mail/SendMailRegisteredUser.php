<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\User;

class SendMailRegisteredUser extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    protected $detail;

    public function __construct($detail)
    {
    	$this->detail = $detail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
    	/*return $this->from('allaravel.com@gmail.com')
                ->view('emailsregistered')->with('user', $this->user);*/
    	return $this->subject('Subject:test Email')->view('emailsregistered')->with('detail',$this->detail);
    }
}
