<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Http\Traits\MailBodyCreater;

class InformSiteAdminAboutSuspiciousActivity extends Mailable
{
    use Queueable, SerializesModels,MailBodyCreater;

    public $admin;
    public $child_admin;
    public $action;
    public $company_name;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($admin, $child_admin, $action, $company_name)
    {
        $this->admin        =   $admin;
        $this->child_admin  = $child_admin;
        $this->action       = $action;
        $this->company_name = $company_name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [];
		$data['full_name']      = $this->admin->full_name;
        $data['child_admin'] 	= $this->child_admin->full_name;
        $data['action'] 	    = $this->action;
        $data['company_name'] 	= $this->company_name;
		if($this->setMailBody(3,$data)){
			return $this->markdown('emails.mail_body')->subject($this->subject);
		}
    }
}
