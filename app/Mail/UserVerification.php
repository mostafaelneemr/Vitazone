<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserVerification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $code;
    public $user;
    public $content;
    public function __construct($code, $user, $content)
    {
        $this->code = $code;
        $this->user = $user;
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.register_user')
            ->from(env('MAIL_USERNAME'), translate(env('MAIL_FROM_NAME')))
            ->subject('Virification code')
            ->with([
                'content' => $this->content,
                'code' => $this->code,
                'user' => $this->user,
            ]);
    }
}
