<?php
declare(strict_types=1);

namespace App\Domain\Builder\Interfaces;

use App\Service\Image\Interfaces\ImageThumbnailCreatorInterface;
use App\Service\Image\Interfaces\ImageUploaderInterface;
use App\Service\Image\Interfaces\ImageUploadWarmerInterface;

interface PictureBuilderInterface
{

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
    );

    /**
     * @param $picturesDTO
     * @param bool $isCollection
     * @param bool $isThumbnailToCreate
     */
    public function create($picturesDTO, bool $isCollection, bool $isThumbnailToCreate = false);
}
