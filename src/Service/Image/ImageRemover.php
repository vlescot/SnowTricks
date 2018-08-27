<?php
declare(strict_types = 1);

namespace App\Service\Image;

use App\Domain\Repository\PictureRepository;
use Symfony\Component\Filesystem\Filesystem;

class ImageRemover
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var PictureRepository
     */
    private $pictureRepository;

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
     * @param PictureRepository $pictureRepository
     * @param string $publicFolder
     */
    public function __construct(
        Filesystem $filesystem,
        PictureRepository $pictureRepository,
        string $publicFolder
    ) {
        $this->filesystem = $filesystem;
        $this->pictureRepository = $pictureRepository;
        $this->publicFolder = $publicFolder;
    }

    /**
     * @param string $path
     * @param string|null $filename
     */
    public function addFileToRemove(string $path, string $filename = null)
    {
        if (null === $filename) {
            $this->filesToRemove[] = $path;
        } else {
            $this->filesToRemove[] = $path .'/'. $filename;
        }
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     */
    public function removeFiles()
    {
        foreach ($this->filesToRemove as $path) {
            $this->filesystem->remove($this->publicFolder . $path);

            $pictureInfo = pathinfo($this->publicFolder . $path);
            $this->pictureRepository->removeByFilename($pictureInfo['basename']);
        }
    }
}
