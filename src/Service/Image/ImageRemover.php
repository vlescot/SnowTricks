<?php
declare(strict_types = 1);

namespace App\Service\Image;

use Symfony\Component\Filesystem\Filesystem;

class ImageRemover
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
    private $filesToRemove = [];

    /**
     * ImageRemover constructor.
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

    public function removeFiles()
    {
        foreach ($this->filesToRemove as $path) {
            try {
                $this->filesystem->remove($this->publicFolder . $path);
            } catch (\Exception $e) {
                dd($e);
                //todo
            }
        }
    }
}
