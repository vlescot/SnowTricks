<?php
declare(strict_types=1);

namespace App\App\Image\Interfaces;

interface ImageUploaderInterface
{
    /**
     * ImageUploader constructor.
     *
     * @param string $publicFolder
     */
    public function __construct(string $publicFolder);

    /**
     * @param \SplFileInfo $file
     * @param string $path
     * @param string $filename
     */
    public function addFileToUpload(\SplFileInfo $file, string $path, string $filename): void;

    public function uploadFiles(): void;
}
