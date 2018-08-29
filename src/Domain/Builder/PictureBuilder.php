<?php
declare(strict_types = 1);

namespace App\Domain\Builder;

use App\Domain\DTO\PictureDTO;
use App\Domain\Entity\Picture;
use App\UI\Service\Image\ImageUploadWarmer;
use App\UI\Service\Image\ImageUploader;
use App\UI\Service\Image\ImageThumbnailCreator;

class PictureBuilder
{
    /**
     * @var ImageUploadWarmer
     */
    private $imageUploadWarmer;

    /**
     * @var ImageUploader
     */
    private $imageUploader;

    /**
     * @var ImageThumbnailCreator
     */
    private $thumbnailCreator;

    /**
     * PictureBuilder constructor.
     *
     * @param ImageUploader $imageUploader
     * @param ImageThumbnailCreator $thumbnailCreator
     * @param ImageUploadWarmer $imageUploadWarmer
     */
    public function __construct(
        ImageUploader $imageUploader,
        ImageThumbnailCreator $thumbnailCreator,
        ImageUploadWarmer $imageUploadWarmer
    ) {
        $this->imageUploader = $imageUploader;
        $this->thumbnailCreator = $thumbnailCreator;
        $this->imageUploadWarmer = $imageUploadWarmer;
    }

    /**
     * @param $picturesDTO
     * @param bool $isCollection
     * @param bool $isThumbnailToCreate
     *
     * @return Picture|array
     *
     * @throws \Exception
     */
    public function create($picturesDTO, bool $isCollection, bool $isThumbnailToCreate = false)
    {
        if ($isCollection) {
            $pictures = [];
            foreach ($picturesDTO as $pictureDTO) {
                if (null !== $pictureDTO->file) {
                    $pictures[] = $this->createPicture($pictureDTO, $isThumbnailToCreate);
                }
            }
            return $pictures;
        }

        return $this->createPicture($picturesDTO, $isThumbnailToCreate);
    }

    /**
     * @param PictureDTO $pictureDTO
     * @param bool $isThumbnailToCreate
     *
     * @return Picture
     *
     * @throws \Exception
     */
    private function createPicture(PictureDTO $pictureDTO, bool $isThumbnailToCreate)
    {
        $pictureInfo = $this->imageUploadWarmer->generateImageInfo($pictureDTO->file);

        $this->imageUploader->addFileToUpload(
            $pictureDTO->file,
            $pictureInfo['path'],
            $pictureInfo['filename']
        );

        if ($isThumbnailToCreate) {
            $picturePathName = $pictureInfo['path'] .'/'. $pictureInfo['filename'];
            $this->thumbnailCreator->addThumbnailToCreate($picturePathName);
        }

        return new Picture(
            $pictureInfo['path'],
            $pictureInfo['filename'],
            $pictureInfo['alt']
        );
    }
}
