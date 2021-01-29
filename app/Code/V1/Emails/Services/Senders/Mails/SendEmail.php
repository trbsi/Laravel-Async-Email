<?php

namespace App\Code\V1\Emails\Services\Senders\Mails;

use App\Code\V1\Emails\Services\Senders\Values\EmailData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * @var EmailData
     */
    private $emailData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(EmailData $emailData)
    {
        $this->emailData = $emailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $builtEmail = $this
            ->to($this->emailData->getEmail())
            ->subject($this->emailData->getSubject())
            ->view('emails.my-mail-template')
            ->with([
                'body' => $this->emailData->getBody()
            ]);

        foreach ($this->emailData->getAttachments() as $attachment) {
            $builtEmail
                ->attachFromStorage(
                    $attachment->getRelativePath(),
                    $attachment->getName()
                );
        }
    }
}
