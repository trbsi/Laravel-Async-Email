<?php

namespace App\Code\V1\Emails\Services\Senders\Services;

use App\Code\V1\Emails\Services\Senders\Values\Attachment;
use App\Code\V1\Emails\Services\Senders\Values\EmailData;
use App\Models\Email;

class LogEmail
{
    public function log(EmailData $emailData)
    {
        $email = new Email();
        $email->email = $emailData->getEmail();
        $email->subject = $emailData->getSubject();
        $email->body = $emailData->getBody();
        $email->save();

        if ([] !== $emailData->getAttachments()) {
            $email->attachments()->createMany($this->prepareAttachmentNames($emailData->getAttachments()));
        }
    }

    /**
     * @param Attachment[] $attachments
     * @return array
     */
    private function prepareAttachmentNames(array $attachments): array
    {
        $data = [];
        foreach ($attachments as $attachment) {
            $data[]['attachment'] = $attachment->getRelativePathWithFileName();
        }

        return $data;
    }
}