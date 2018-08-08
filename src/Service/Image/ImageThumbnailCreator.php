<?php
declare(strict_types = 1);

namespace App\Service\Image;

use function PHPSTORM_META\type;
use Symfony\Component\Filesystem\Filesystem;

class ImageThumbnailCreator
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
     * ImageThumbnailCreator constructor.
     *
     * @param Filesystem $filesystem
     * @param string $publicFolder
     * @param string $thumbnailsWidth
     * @param string $thumbnailsAltPrefix
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
     * @param string $imagePathName
     */
    public function addThumbnailToCreate(string $imagePathName)
    {
        $this->thumbnailToCreate[] = $imagePathName;
    }

    /**
     * @return array|bool
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
