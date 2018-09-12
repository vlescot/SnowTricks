<?php
declare(strict_types = 1);

namespace App\Domain\Builder;

use App\Domain\Builder\Interfaces\GroupBuilderInterface;
use App\Domain\Builder\Interfaces\PictureBuilderInterface;
use App\Domain\Builder\Interfaces\UpdateTrickBuilderInterface;
use App\Service\CollectionManager\Interfaces\CollectionUpdatePrepareInterface;
use App\Domain\DTO\Interfaces\TrickDTOInterface;
use App\Domain\Entity\Interfaces\TrickInterface;
use App\Service\Image\Interfaces\ImageRemoverInterface;
use App\Service\Image\Interfaces\ImageUploadWarmerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class UpdateTrickBuilder implements UpdateTrickBuilderInterface
{
    /**
     * @var CollectionUpdatePrepareInterface
     */
    private $collectionPrepare;

    /**
     * @var ImageUploadWarmerInterface
     */
    private $imageUploadWarmer;

    /**
     * @var ImageRemoverInterface
     */
    private $imageRemover;

    /**
     * @var GroupBuilderInterface
     */
    private $groupBuilder;

    /**
     * @var PictureBuilderInterface
     */
    private $pictureBuilder;

    /**
     * UpdateTrickBuilder constructor.
     *
     * @param CollectionUpdatePrepareInterface $collectionPrepare
     * @param ImageUploadWarmerInterface $imageUploadWarmer
     * @param ImageRemoverInterface $imageRemover
     * @param GroupBuilderInterface $groupBuilder
     * @param PictureBuilderInterface $pictureBuilder
     */
    public function __construct(
        CollectionUpdatePrepareInterface $collectionPrepare,
        ImageUploadWarmerInterface $imageUploadWarmer,
        ImageRemoverInterface $imageRemover,
        GroupBuilderInterface $groupBuilder,
        PictureBuilderInterface $pictureBuilder
    ) {
        $this->collectionPrepare = $collectionPrepare;
        $this->imageUploadWarmer = $imageUploadWarmer;
        $this->imageRemover = $imageRemover;
        $this->groupBuilder = $groupBuilder;
        $this->pictureBuilder = $pictureBuilder;
    }

    /**
     * @param TrickInterface $trick
     * @param TrickDTOInterface $trickDTO
     *
     * @return TrickInterface
     */
    public function update(TrickInterface $trick, TrickDTOInterface $trickDTO): TrickInterface
    {
        $this->imageUploadWarmer->initialize('trick', $trickDTO->title);

        if ($trickDTO->mainPicture->file instanceof UploadedFile) {
            $mainPicture = $this->pictureBuilder->create($trickDTO->mainPicture, false, true);
            $this->imageRemover->addFileToRemove($trick->getMainPicture());
        }

        $trick->update(
            $trickDTO->title,
            $trickDTO->description,
            $mainPicture ?? $trick->getMainPicture(),
            $this->collectionPrepare->prepare($trick->getPictures()->toArray(), $trickDTO->pictures),
            $this->collectionPrepare->prepare($trick->getVideos()->toArray(), $trickDTO->videos),
            $this->groupBuilder->create($trickDTO->groups, $trickDTO->newGroups)
        );

        return $trick;
    }
}
