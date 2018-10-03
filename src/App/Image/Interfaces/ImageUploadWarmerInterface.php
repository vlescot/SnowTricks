<?php
declare(strict_types=1);

namespace App\App\Image\Interfaces;

use Symfony\Component\Filesystem\Filesystem;

interface ImageUploadWarmerInterface
{
    /**
     * ImageUploadWarmer constructor.
     *
     * @param Filesystem $filesystem
     * @param string $publicFolder
     * @param string $trickFolder
     * @param string $userFolder
     */
    public function __construct(Filesystem $filesystem, string $publicFolder, string $trickFolder, string $userFolder);

    /**
     * @param string $context
     * @param string $title
     *
     * @return mixed
     */
    public function initialize(string $context, string $title);

    /**
     * @param \SplFileInfo $file
     *
     * @return array
     */
    public function generateImageInfo(\SplFileInfo $file): array;

    /**
     * @return array
     */
    public function getUpdateImageInfo(): array;
}
