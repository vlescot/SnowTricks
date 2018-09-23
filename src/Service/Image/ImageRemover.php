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
     * {@inheritdoc}
     */
    public function __construct(
        Filesystem $filesystem,
        string $publicFolder
    ) {
        $this->filesystem = $filesystem;
        $this->publicFolder = $publicFolder;
    }


    /**
     * {@inheritdoc}
     */
    public function addFileToRemove(PictureInterface $picture): void
    {
        $this->filesToRemove[] = $picture;
    }

    /**
     * {@inheritdoc}
     */
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
