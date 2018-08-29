<?php
declare(strict_types = 1);

namespace App\UI\Service\Image;

use Symfony\Component\Filesystem\Filesystem;

class FolderChanger
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
     * ContentDirectoryChanger constructor.
     *
     * @param Filesystem $filesystem
     * @param string $publicFolder
     */
    public function __construct(Filesystem $filesystem, string $publicFolder)
    {
        $this->filesystem = $filesystem;
        $this->publicFolder = $publicFolder;
    }

    public function folderToChange(string $oldPath, string $newPath)
    {
        $this->changeDirectoryFromTo = [
            'oldPath' => $this->publicFolder . $oldPath,
            'newPath' => $this->publicFolder . $newPath
        ];
    }

    public function changeFilesDirectory()
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
