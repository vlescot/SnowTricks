<?php
declare(strict_types=1);

namespace App\App\Image\Interfaces;

use Symfony\Component\Filesystem\Filesystem;

interface FolderRemoverInterface
{
    /**
     * FolderRemover constructor.
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
     */
    public function removeFolder(string $path): void;
}
