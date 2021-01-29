<?php

namespace App\Code\V1\Emails\Services\Senders\Jobs;

use App\Code\V1\Emails\Services\Senders\Mails\SendEmail;
use App\Code\V1\Emails\Services\Senders\Services\LogEmails;
use App\Code\V1\Emails\Services\Senders\Services\ProcessAttachments;
use App\Code\V1\Emails\Services\Senders\Values\EmailData;
use App\Models\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProcessEmailsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $subject;
    /**
     * @var string
     */
    private $body;
    /**
     * @var array
     */
    private $attachments;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        string $email,
        string $subject,
        string $body,
        array $attachments = []
    ) {
        $this->email = $email;
        $this->subject = $subject;
        $this->body = $body;
        $this->attachments = $attachments;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ProcessAttachments $processAttachments, LogEmails $logEmails)
    {
        $attachments = $processAttachments->process($this->attachments);
        $emailData = new EmailData(
            $this->email,
            $this->subject,
            $this->body,
            $attachments,
        );
        Mail::queue(new SendEmail($emailData));

        $logEmails->log($emailData);
    }
}
