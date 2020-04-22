<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BlogMailing extends Mailable
{
    use Queueable, SerializesModels;

    public $mailing_body;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($mailing_body)
    {
        $this->mailing_body = $mailing_body;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('blog@admin.com')
                    ->view('emails.blog-mailing');
    }
}
