<?php
declare(strict_types = 1);

namespace App\UI\Service\Image;

class ImageUploader
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
     * ImageUploader constructor.
     *
     * @param string $publicFolder
     */
    public function __construct(string $publicFolder)
    {
        $this->publicFolder = $publicFolder;
    }

    public function addFileToUpload(\SplFileInfo $file, string $path, string $filename)
    {
        $this->filesToUpload [] = [
            'file' => $file,
            'path' => $path,
            'filename' => $filename
        ];
    }

    public function uploadFiles()
    {
        foreach ($this->filesToUpload as $fileToUpload) {
            $pathDir = $this->publicFolder . $fileToUpload['path'];
            $fileToUpload['file']->move($pathDir, $fileToUpload['filename']);
        }
    }
}
