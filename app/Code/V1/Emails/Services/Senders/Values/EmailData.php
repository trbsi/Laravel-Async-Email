<?php

namespace App\Code\V1\Emails\Services\Senders\Values;

class EmailData
{
    /**
     * @var string
     */
    private string $email;
    /**
     * @var string
     */
    private string $subject;
    /**
     * @var string
     */
    private string $body;
    /**
     * @var Attachment[]
     */
    private array $attachments;

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

    public function __toString()
    {
        return sprintf('%s | %s | %s', $this->email, $this->subject, $this->body);
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @return Attachment[]
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }
}