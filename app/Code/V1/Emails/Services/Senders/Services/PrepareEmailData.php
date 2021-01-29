<?php

namespace App\Code\V1\Emails\Services\Senders\Services;

use App\Code\V1\Emails\Services\Senders\Values\EmailData;

class PrepareEmailData
{
    /**
     * @var ProcessAttachments
     */
    private ProcessAttachments $processAttachments;

    public function __construct(ProcessAttachments $processAttachments)
    {
        $this->processAttachments = $processAttachments;
    }

    public function prepareData(
        string $email,
        string $subject,
        string $body,
        array $attachments = []
    ): EmailData {
        $attachments = $this->processAttachments->process($attachments);
        $emailData = new EmailData(
            $email,
            $subject,
            $body,
            $attachments,
        );

        return $emailData;
    }
}