<?php


namespace App\Traits;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OutGoingMail extends Mailable
{
    use Queueable, SerializesModels;

    private $param;

    /**
     * Create a new message instance.
     *
     * @param $param
     */
    public function __construct($param)
    {
        $this->param = $param;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject($this->param['subject']);

        if (isset($this->param['bcc'])) {
            $bcc = $this->param['bcc'];

            if (is_array($bcc)) {
                $this->bcc = $bcc;
            } else {
                $this->bcc($bcc);
            }
        }

        if (isset($this->param['cc'])) {
            $cc = $this->param['cc'];

            if (is_array($cc)) {
                $this->cc = $cc;
            } else {
                $this->cc($cc);
            }
        }

        if (isset($this->param['to'])) {
            $to = $this->param['to'];

            if (is_array($to)) {
                $this->to = $to;
            } else {
                $this->to($to);
            }
        }
        $view = 'email-templates.general_template';

        if (isset($this->param['view'])) {
            $view = $this->param['view'];
            unset($this->param['view']); //Remove after use
        }

        return $this->view($view, $this->param);
    }
}
