<?php
declare(strict_types = 1);

namespace App\Domain\Builder;

use App\Domain\Builder\Interfaces\GroupBuilderInterface;
use App\Domain\Builder\Interfaces\PictureBuilderInterface;
use App\Domain\Builder\Interfaces\UpdateTrickBuilderInterface;
use App\Service\CollectionManager\Interfaces\CollectionUpdatePrepareInterface;
use App\Domain\DTO\Interfaces\TrickDTOInterface;
use App\Domain\Entity\Interfaces\TrickInterface;
use App\Service\Image\Interfaces\FolderChangerInterface;
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
     * @var FolderChangerInterface
     */
    private $folderChanger;

    /**
     * @var GroupBuilderInterface
     */
    private $groupBuilder;

    /**
     * @var PictureBuilderInterface
     */
    private $pictureBuilder;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        CollectionUpdatePrepareInterface $collectionPrepare,
        ImageUploadWarmerInterface $imageUploadWarmer,
        ImageRemoverInterface $imageRemover,
        FolderChangerInterface $folderChanger,
        GroupBuilderInterface $groupBuilder,
        PictureBuilderInterface $pictureBuilder
    ) {
        $this->collectionPrepare = $collectionPrepare;
        $this->imageUploadWarmer = $imageUploadWarmer;
        $this->imageRemover = $imageRemover;
        $this->folderChanger = $folderChanger;
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

        // Change the pictures's folder if the title change
        if ($trickDTO->title !== $trick->getTitle()) {
            $updatePictureInfo = $this->imageUploadWarmer->getUpdateImageInfo();
            $this->folderChanger->folderToChange($trick->getMainPicture()->getPath(), $updatePictureInfo['path']);

            $trick->getMainPicture()->setPath($updatePictureInfo['path']);
            foreach ($trick->getPictures() as $picture) {
                $picture->setPath($updatePictureInfo['path']);
            }
        }


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
