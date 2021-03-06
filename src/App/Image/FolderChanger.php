<?php
declare(strict_types = 1);

namespace App\App\Image;

use App\App\Image\Interfaces\FolderChangerInterface;
use Symfony\Component\Filesystem\Filesystem;

final class FolderChanger implements FolderChangerInterface
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
    private $changeDirectoryFromTo = [];

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
    public function folderToChange(string $oldPath, string $newPath): void
    {
        $this->changeDirectoryFromTo = [
            'oldPath' => $this->publicFolder . $oldPath,
            'newPath' => $this->publicFolder . $newPath
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function changeFilesDirectory(): void
    {
        if ($this->changeDirectoryFromTo) {
            $newPath = $this->changeDirectoryFromTo['newPath'];
            $oldPath = $this->changeDirectoryFromTo['oldPath'];

            $this->filesystem->mkdir($newPath);

            $files = scandir($oldPath);
            foreach ($files as $file) {
                if (!in_array($file, [".",".."])) {
                    $filePath = $oldPath . '/' . $file;

                    $this->filesystem->copy($filePath, $newPath .'/'. $file);
                    $this->filesystem->remove($filePath);
                }
            }
            $this->filesystem->remove($oldPath);
        }
    }
}
