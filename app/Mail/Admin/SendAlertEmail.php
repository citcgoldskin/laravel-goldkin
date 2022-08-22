<?php

namespace App\Mail\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Lang;
use Auth;

class SendAlertEmail extends Mailable
{
    use Queueable, SerializesModels;

    private $params;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('const.noreply_email'))
            ->subject(config('const.mail_subject_prefix').$this->params['alert_title'])
            ->markdown('mails.send_alert', [
                'alert_content'=>$this->params['alert_text']
            ]);
    }
}
