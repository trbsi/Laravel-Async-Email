<?php


namespace App\Code\V1\Emails\Services\Senders\Values;


class Attachment
{
    /**
     * @var string
     */
    private string $relativePath;
    /**
     * @var string
     */
    private string $name;
    /**
     * @var string
     */
    private $relativePathWithFileName;

    public function __construct(
        string $relativePath,
        string $relativePathWithFileName,
        string $name
    ) {
        $this->relativePath = $relativePath;
        $this->relativePathWithFileName = $relativePathWithFileName;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getRelativePath(): string
    {
        return $this->relativePath;
    }

    /**
     * @return string
     */
    public function getRelativePathWithFileName(): string
    {
        return $this->relativePathWithFileName;
    }


    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}