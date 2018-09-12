<?php
declare(strict_types = 1);

namespace App\Domain\Builder;

use App\Domain\Builder\Interfaces\PictureBuilderInterface;
use App\Domain\DTO\Interfaces\PictureDTOInterface;
use App\Domain\Entity\Picture;
use App\Service\Image\Interfaces\ImageThumbnailCreatorInterface;
use App\Service\Image\Interfaces\ImageUploaderInterface;
use App\Service\Image\Interfaces\ImageUploadWarmerInterface;

final class PictureBuilder implements PictureBuilderInterface
{
    /**
     * @var ImageUploadWarmerInterface
     */
    private $imageUploadWarmer;

    /**
     * @var ImageUploaderInterface
     */
    private $imageUploader;

    /**
     * @var ImageThumbnailCreatorInterface
     */
    private $thumbnailCreator;

    /**
     * PictureBuilder constructor.
     *
     * @param ImageUploaderInterface $imageUploader
     * @param ImageThumbnailCreatorInterface $thumbnailCreator
     * @param ImageUploadWarmerInterface $imageUploadWarmer
     */
    public function __construct(
        ImageUploaderInterface $imageUploader,
        ImageThumbnailCreatorInterface $thumbnailCreator,
        ImageUploadWarmerInterface $imageUploadWarmer
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
     * @param PictureDTOInterface $pictureDTO
     * @param bool $isThumbnailToCreate
     *
     * @return Picture
     *
     * @throws \Exception
     */
    private function createPicture(PictureDTOInterface $pictureDTO, bool $isThumbnailToCreate)
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
