<?php
declare(strict_types=1);

namespace App\UI\Service\Image;

use Symfony\Component\Filesystem\Filesystem;

class FolderRemover
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
     * FolderRemover constructor.
     *
     * @param Filesystem $filesystem
     * @param string $publicFolder
     */
    public function __construct(Filesystem $filesystem, string $publicFolder)
    {
        $this->filesystem = $filesystem;
        $this->publicFolder = $publicFolder;
    }

    /**
     * @param string $path
     */
    public function removeFolder(string $path)
    {
        $this->filesystem->remove($this->publicFolder . $path);
    }
}
