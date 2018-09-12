<?php
declare(strict_types=1);

namespace App\Service\Image\Interfaces;

use Symfony\Component\Filesystem\Filesystem;

interface FolderChangerInterface
{
    /**
     * ContentDirectoryChanger constructor.
     *
     * @param Filesystem $filesystem
     * @param string $publicFolder
     */
    public function __construct(Filesystem $filesystem, string $publicFolder);

    /**
     * @param string $oldPath
     * @param string $newPath
     *
     * @return mixed
     */
    public function folderToChange(string $oldPath, string $newPath): void;

    public function changeFilesDirectory(): void;
}
