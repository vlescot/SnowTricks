<?php
declare(strict_types = 1);

namespace App\UI\Service\Image;

use App\Domain\Entity\Picture;
use Symfony\Component\Filesystem\Filesystem;

class ImageRemover
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var FolderRemover
     */
    private $folderRemover;

    /**
     * @var string
     */
    private $publicFolder;

    /**
     * @var array
     */
    private $filesToRemove = [];

    /**
     * ImageRemover constructor.
     *
     * @param Filesystem $filesystem
     * @param FolderRemover $folderRemover
     * @param string $publicFolder
     */
    public function __construct(Filesystem $filesystem, FolderRemover $folderRemover, string $publicFolder)
    {
        $this->filesystem = $filesystem;
        $this->folderRemover = $folderRemover;
        $this->publicFolder = $publicFolder;
    }


    /**
     * @param Picture $picture
     */
    public function addFileToRemove(Picture $picture)
    {
        $this->filesToRemove[] = $picture;
    }

    public function removeFiles()
    {
        foreach ($this->filesToRemove as $picture) {
            $thumbnailPath = $this->publicFolder . $picture->getPath() . '/thumbnail-' . $picture->getFilename();
            if ($this->filesystem->exists($thumbnailPath)) {
                $this->filesystem->remove($thumbnailPath);
            }
            $this->filesystem->remove($this->publicFolder . $picture->getWebPath());
        }
    }
}
