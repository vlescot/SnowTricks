<?php
declare(strict_types=1);

namespace App\App\Image\Interfaces;

use App\Domain\Entity\Interfaces\PictureInterface;
use Symfony\Component\Filesystem\Filesystem;

interface ImageRemoverInterface
{
    /**
     * ImageRemover constructor.
     *
     * @param Filesystem $filesystem
     * @param string $publicFolder
     */
    public function __construct(
        Filesystem $filesystem,
        string $publicFolder
    );

    /**
     * @param string $path
     * @return mixed
     */
    public function warmFolder(string $path): void;

    /**
     * @param PictureInterface $picture
     * @return mixed
     */
    public function addFileToRemove(PictureInterface $picture): void;

    public function removeFiles(): void;
}
