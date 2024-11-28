<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewMemberNotify extends Mailable
{
    use Queueable, SerializesModels;

    public $bookName;
    public $branchName;

    /**
     * Create a new message instance.
     *
     * @param string $bookName
     * @param string $branchName
     */
    public function __construct($bookName, $branchName)
    {
        $this->bookName = $bookName;
        $this->branchName = $branchName;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Book Return Notification')
                    ->view('emails.return-notification');
    }
}



