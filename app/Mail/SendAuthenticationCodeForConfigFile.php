<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Http\Traits\MailBodyCreater;

class SendAuthenticationCodeForConfigFile extends Mailable
{
    use Queueable, SerializesModels,MailBodyCreater;

    public $user;
    public $code;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $code)
    {
        $this->user = $user;
        $this->code = $code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [];
		$data['full_name']  = $this->user->full_name;
        $data['code'] 		= $this->code;
		if($this->setMailBody(2,$data)){
			return $this->markdown('emails.mail_body')->subject($this->subject);
		}
    }
}
