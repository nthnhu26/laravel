<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Contact;

class ContactReply extends Mailable
{
    use Queueable, SerializesModels;

    public $contact;
    public $replyMessage;

    public function __construct(Contact $contact, $replyMessage)
    {
        $this->contact = $contact;
        $this->replyMessage = $replyMessage;
    }

    public function build()
    {
        return $this->subject('Phản hồi cho liên hệ của bạn')
                    ->view('emails.contact-reply');
    }
}