<?php
declare(strict_types = 1);

namespace App\Service\Image;

use App\Domain\Entity\Interfaces\PictureInterface;
use App\Service\Image\Interfaces\ImageRemoverInterface;
use Symfony\Component\Filesystem\Filesystem;

final class ImageRemover implements ImageRemoverInterface
{
    /**
     * @var Filesystem
     */
    private $filesystem;

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
     * @param string $publicFolder
     */
    public function __construct(
        Filesystem $filesystem,
        string $publicFolder
    ) {
        $this->filesystem = $filesystem;
        $this->publicFolder = $publicFolder;
    }


    /**
     * @param PictureInterface $picture
     */
    public function addFileToRemove(PictureInterface $picture): void
    {
        $this->filesToRemove[] = $picture;
    }

    public function removeFiles(): void
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
