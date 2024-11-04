<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApproveTryout extends Mailable
{
    use Queueable, SerializesModels;

    protected $subject;
    protected $view;
    protected $data;

    public function __construct($subject, $view, $data)
    {
        $this->subject = $subject;
        $this->view = $view;
        $this->data = $data;
    }

    public function build()
    {
        return $this->subject($this->subject)
                    ->view($this->view)
                    ->with('data', $this->data);
    }
}


