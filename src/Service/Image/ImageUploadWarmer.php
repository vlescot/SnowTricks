<?php
declare(strict_types = 1);

namespace App\Service\Image;

use App\Service\Image\Interfaces\ImageUploadWarmerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class ImageUploadWarmer
 * @package App\Service
 */
final class ImageUploadWarmer implements ImageUploadWarmerInterface
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
     * @inheritdoc
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
     * @inheritdoc
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

    /**
     * @inheritdoc
     */
    public function generateImageInfo(\SplFileInfo $file): array
    {
        return [
            'filename' => md5(uniqid('', true)) .'.'. $file->guessExtension(),
            'path' => $this->path,
            'alt' => $this->alt
        ];
    }

    /**
     * @inheritdoc
     */
    public function getUpdateImageInfo(): array
    {
        return [
            'path' => $this->path,
            'alt' => $this->alt
        ];
    }
}
