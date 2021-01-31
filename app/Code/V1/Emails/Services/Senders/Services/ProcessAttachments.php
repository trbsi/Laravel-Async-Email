<?php

namespace App\Code\V1\Emails\Services\Senders\Services;

use App\Code\V1\Emails\Services\Senders\Exceptions\AttachmentCouldNotBeProcessedException;
use App\Code\V1\Emails\Services\Senders\Exceptions\InvalidBase64StringException;
use App\Code\V1\Emails\Services\Senders\Values\Attachment;
use Illuminate\Http\File;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProcessAttachments
{
    private const FILE_PATH = 'email_attachments';

    public function process(array $attachments): array
    {
        if (empty($attachments)) {
            return [];
        }

        $data = [];
        foreach ($attachments as $attachment) {
            try {
                [$mimeType, $fileBase64] = $this->getMimeTypeAndBase64String($attachment['value']);
            } catch (InvalidBase64StringException $e) {
                continue;
            }

            $decodedFile = base64_decode(trim($fileBase64), true);
            if (false === $decodedFile) {
                continue;
            }

            try  {
                $fileExtension = $this->getFileExtension($mimeType);
            } catch (AttachmentCouldNotBeProcessedException $e) {
                continue;
            }

            $fileName = Str::slug($attachment['name']);


            [$relativePath, $relativePathWithFile] = $this->getFilePaths($fileExtension, $fileName);
            Storage::put(
                $relativePathWithFile,
                $decodedFile,
            );

            $data[] = new Attachment($relativePath, $relativePathWithFile, $attachment['name']);
        }

        return $data;
    }

    private function getFileExtension(string $mimeType): string
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

    private function getMimeTypeAndBase64String(string $attachment): array
    {
        $fileData = explode(',', $attachment);

        if (1 === count($fileData)) {
            throw new InvalidBase64StringException();
        }

        return [
            $fileData[0],
            $fileData[1]
        ];
    }

    private function getFilePaths(string $fileExtension, string $fileName): array
    {
        $folderIdentification = Carbon::now()->format('Y-m-d');

        $relativePath = sprintf('%s/%s', self::FILE_PATH, $folderIdentification);
        $fileNameWithExtension = sprintf('%s.%s', $fileName, $fileExtension);
        $relativePathWithFile = sprintf('%s/%s', $relativePath, $fileNameWithExtension);

        return [
            $relativePath,
            $relativePathWithFile,
        ];
    }
}