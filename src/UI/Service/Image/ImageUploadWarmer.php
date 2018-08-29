<?php
declare(strict_types = 1);

namespace App\UI\Service\Image;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Class ImageUploadWarmer
 * @package App\Service
 */
class ImageUploadWarmer
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
     * @var string
     */
    private $trickFolder;

    /**
     * @var string
     */
    private $userFolder;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $alt;

    /**
     * ImageUploadWarmer constructor.
     *
     * @param Filesystem $filesystem
     * @param string $publicFolder
     * @param string $trickFolder
     * @param string $userFolder
     */
    public function __construct(
        Filesystem $filesystem,
        string $publicFolder,
        string $trickFolder,
        string $userFolder
    ) {
        $this->filesystem = $filesystem;
        $this->publicFolder = $publicFolder;
        $this->trickFolder = $trickFolder;
        $this->userFolder = $userFolder;
    }

    /**
     * @param string $context
     * @param string $title
     */
    public function initialize(string $context, string $title)
    {
        switch ($context) {
            case 'trick':
                $currentFolder = $this->trickFolder;
                break;
            case 'user':
                $currentFolder = $this->userFolder;
                break;
        }

        $this->title = $title;
        $this->path = $currentFolder . '/' . strtolower(str_replace(' ', '_', $title));
        $this->alt = 'image-' . strtolower(str_replace(' ', '-', $title));

        $this->filesystem->mkdir($this->publicFolder . $this->path);
    }

    public function generateImageInfo(\SplFileInfo $file)
    {
        return [
            'filename' => md5(uniqid('', true)) .'.'. $file->guessExtension(),
            'path' => $this->path,
            'alt' => $this->alt
        ];
    }

    public function getUpdateImageInfo()
    {
        return [
            'path' => $this->path,
            'alt' => $this->alt
        ];
    }
}
