<?php
declare(strict_types = 1);

namespace App\App\Image;

use App\App\Image\Interfaces\ImageThumbnailCreatorInterface;
use Symfony\Component\Filesystem\Filesystem;

final class ImageThumbnailCreator implements ImageThumbnailCreatorInterface
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
    private $thumbnailsWidth;

    /**
     * @var string
     */
    private $thumbnailsAltPrefix;

    /**
     * @var array
     */
    private $thumbnailToCreate = [];

    /**
     * {@inheritdoc}
     */
    public function __construct(
        Filesystem $filesystem,
        string $publicFolder,
        string $thumbnailsWidth,
        string $thumbnailsAltPrefix
    ) {
        $this->filesystem = $filesystem;
        $this->publicFolder = $publicFolder;
        $this->thumbnailsWidth = intval($thumbnailsWidth);
        $this->thumbnailsAltPrefix = $thumbnailsAltPrefix;
    }

    /**
     * {@inheritdoc}
     */
    public function addThumbnailToCreate(string $imagePathName)
    {
        $this->thumbnailToCreate[] = $imagePathName;
    }

    /**
     * {@inheritdoc}
     */
    public function createThumbnails()
    {
        $errors = [];

        foreach ($this->thumbnailToCreate as $imagePathName) {
            $initialPath = $this->publicFolder . $imagePathName;

            $imageInfo = pathinfo($initialPath);
            $fileExtension = $imageInfo['extension'];
            $finalPath = $imageInfo['dirname'] . '/' . $this->thumbnailsAltPrefix . $imageInfo['basename'];

            $this->filesystem->copy($initialPath, $finalPath);

            $width = $this->thumbnailsWidth;
            $imageSize 	= getimagesize($finalPath);// [0]=>width, [1]=>height (Image Original)
            $ratio 		= $imageSize[1]/$imageSize[0];
            $height 	= intval(ceil($width * $ratio));

            switch ($fileExtension) {
                case 'png': $imageRessource = imagecreatefrompng($finalPath);
                    break;
                case 'jpg': case 'jpeg': $imageRessource = imagecreatefromjpeg($finalPath);
                break;
            }

            $finalImage = imagecreatetruecolor($width, $height); // creates an empty miniature
            imagecopyresampled($finalImage, $imageRessource, 0, 0, 0, 0, $width, $height, $imageSize[0], $imageSize[1]);

            switch ($fileExtension) {
                case 'png': $result = imagepng($finalImage, $finalPath);
                    break;
                case 'jpg': case 'jpeg': $result = imagejpeg($finalImage, $finalPath);
                break;
            }

            if (false === $result) {
                $errors[] = $imagePathName;
            }
        }

        if ($errors) {
            return $errors;
        } else {
            return true;
        }
    }
}
