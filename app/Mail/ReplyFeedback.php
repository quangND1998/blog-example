<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReplyFeedback extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $content;
    public $reply;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $content, string $reply)
    {
        $this->content = $content;
        $this->reply = $reply;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.reply-feedback');
    }
}
