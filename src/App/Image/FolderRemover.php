<?php
declare(strict_types=1);

namespace App\App\Image;

use App\App\Image\Interfaces\FolderRemoverInterface;
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
     * {@inheritdoc}
     */
    public function __construct(Filesystem $filesystem, string $publicFolder)
    {
        $this->filesystem = $filesystem;
        $this->publicFolder = $publicFolder;
    }

    /**
     * {@inheritdoc}
     */
    public function removeFolder(string $path): void
    {
        $this->filesystem->remove($this->publicFolder . $path);
    }
}
