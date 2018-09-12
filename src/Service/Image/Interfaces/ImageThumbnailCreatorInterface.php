<?php
declare(strict_types=1);

namespace App\Service\Image\Interfaces;

use Symfony\Component\Filesystem\Filesystem;

interface ImageThumbnailCreatorInterface
{
    /**
     * ImageThumbnailCreator constructor.
     *
     * @param Filesystem $filesystem
     * @param string $publicFolder
     * @param string $thumbnailsWidth
     * @param string $thumbnailsAltPrefix
     */
    public function __construct(
        Filesystem $filesystem,
        string $publicFolder,
        string $thumbnailsWidth,
        string $thumbnailsAltPrefix
    );

    /**
     * @param string $imagePathName
     */
    public function addThumbnailToCreate(string $imagePathName);

    public function createThumbnails();
}
