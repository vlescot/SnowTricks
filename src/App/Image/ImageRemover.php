<?php
declare(strict_types = 1);

namespace App\App\Image;

use App\Domain\Entity\Interfaces\PictureInterface;
use App\App\Image\Interfaces\ImageRemoverInterface;
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
     * @var string
     */
    private $folderPath;

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
    public function warmFolder(string $path): void
    {
        $this->folderPath = $this->publicFolder . $path;
    }

    /**
     * {@inheritdoc}
     */
    public function addFileToRemove(PictureInterface $picture): void
    {
        if (isset($this->folderPath)) {
            $this->filesToRemove[] = $this->folderPath . '/thumbnail-' . $picture->getFilename();
            $this->filesToRemove[] = $this->folderPath . '/' . $picture->getFilename();
        } else {
            $this->filesToRemove[] = $this->publicFolder . $picture->getPath() . '/thumbnail-' . $picture->getFilename();
            $this->filesToRemove[] = $this->publicFolder . $picture->getWebPath();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeFiles(): void
    {
        $this->filesystem->remove($this->filesToRemove);
    }
}
