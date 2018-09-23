<?php
declare(strict_types = 1);

namespace App\Service\Image;

use App\Service\Image\Interfaces\ImageUploaderInterface;

final class ImageUploader implements ImageUploaderInterface
{
    /**
     * @var string
     */
    private $publicFolder;

    /**
     * @var array
     */
    private $filesToUpload = [];

    /**
     * {@inheritdoc}
     */
    public function __construct(string $publicFolder)
    {
        $this->publicFolder = $publicFolder;
    }

    /**
     * {@inheritdoc}
     */
    public function addFileToUpload(\SplFileInfo $file, string $path, string $filename): void
    {
        $this->filesToUpload [] = [
            'file' => $file,
            'path' => $path,
            'filename' => $filename
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function uploadFiles(): void
    {
        foreach ($this->filesToUpload as $fileToUpload) {
            $pathDir = $this->publicFolder . $fileToUpload['path'];
            $fileToUpload['file']->move($pathDir, $fileToUpload['filename']);
        }
    }
}
