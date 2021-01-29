<?php


namespace App\Code\V1\Emails\Services\Senders\Services;

use App\Code\V1\Emails\Services\Senders\Exceptions\AttachmentCouldNotBeProcessedException;
use App\Code\V1\Emails\Services\Senders\Values\Attachment;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProcessAttachments
{
    public function process(array $attachments): array
    {
        $folderIdentification = (new \DateTimeImmutable('now'))->format('Y-m-d');
        $data = [];
        foreach ($attachments as $attachment) {
            $fileData = explode(',', $attachment['value']);
            $mimeType = $fileData[0];
            $fileBase64 = $fileData[1];

            $fileName = Str::slug($attachment['name']);
            $decodedFile = base64_decode(trim($fileBase64));

            $relativePath = sprintf('email_attachments/%s', $folderIdentification,);
            $fileNameWithExtension = sprintf('%s.%s', $fileName, $this->getFileExtension($mimeType));
            $relativePathWithFile = sprintf('%s/%s', $relativePath, $fileNameWithExtension);
            Storage::put(
                $relativePathWithFile,
                $decodedFile,
            );

            $data[] = new Attachment($relativePath, $relativePathWithFile, $attachment['name']);
        }

        return $data;
    }

    private function getFileExtension(string $mimeType)
    {
        switch (true) {
            case str_contains($mimeType, 'pdf'):
                return 'pdf';
            case str_contains($mimeType, 'jpeg') || str_contains($mimeType, 'jpg'):
                return 'jpg';
            case str_contains($mimeType, 'png'):
                return 'png';
            default:
                throw new AttachmentCouldNotBeProcessedException;
        }
    }
}