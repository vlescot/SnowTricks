<?php
declare(strict_types=1);

namespace App\Service\Image\Interfaces;

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
     * @param PictureInterface $picture
     * @return mixed
     */
    public function addFileToRemove(PictureInterface $picture): void;

    public function removeFiles(): void;
}
