<?php
declare(strict_types=1);

namespace App\Service\Image;

use App\Service\Image\Interfaces\FolderRemoverInterface;
use Symfony\Component\Filesystem\Filesystem;

final class FolderRemover implements FolderRemoverInterface
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
    public function removeFolder(string $path): void
    {
        $this->filesystem->remove($this->publicFolder . $path);
    }
}
