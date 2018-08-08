<?php
declare(strict_types = 1);

namespace App\Domain\Builder;

use App\Domain\CollectionManager\CollectionUpdatePrepare\PictureCollectionUpdatePrepare;
use App\Domain\CollectionManager\CollectionUpdatePrepare\VideoCollectionUpdatePrepare;
use App\Domain\DTO\TrickDTO;
use App\Domain\Entity\Trick;
use App\Service\Image\FolderChanger;
use App\Service\Image\ImageRemover;
use App\Service\Image\ImageUploadWarmer;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UpdateTrickBuilder
{
    /**
     * @var PictureCollectionUpdatePrepare
     */
    private $pictureCollectionUpdatePrepare;

    /**
     * @var VideoCollectionUpdatePrepare
     */
    private $videoCollectionUpdatePrepare;

    /**
     * @var ImageUploadWarmer
     */
    private $imageUploadWarmer;

    /**
     * @var ImageRemover
     */
    private $imageRemover;

    /**
     * @var FolderChanger
     */
    private $folderChanger;

    /**
     * @var GroupBuilder
     */
    private $groupBuilder;

    /**
     * @var PictureBuilder
     */
    private $pictureBuilder;

    /**
     * UpdateTrickBuilder constructor.
     *
     * @param PictureCollectionUpdatePrepare $pictureCollectionUpdatePrepare
     * @param VideoCollectionUpdatePrepare $videoCollectionUpdatePrepare
     * @param ImageUploadWarmer $imageUploadWarmer
     * @param ImageRemover $imageRemover
     * @param FolderChanger $folderChanger
     * @param GroupBuilder $groupBuilder
     * @param PictureBuilder $pictureBuilder
     */
    public function __construct(
        PictureCollectionUpdatePrepare $pictureCollectionUpdatePrepare,
        VideoCollectionUpdatePrepare $videoCollectionUpdatePrepare,
        ImageUploadWarmer $imageUploadWarmer,
        ImageRemover $imageRemover,
        FolderChanger $folderChanger,
        GroupBuilder $groupBuilder,
        PictureBuilder $pictureBuilder
    ) {
        $this->pictureCollectionUpdatePrepare = $pictureCollectionUpdatePrepare;
        $this->videoCollectionUpdatePrepare = $videoCollectionUpdatePrepare;
        $this->imageUploadWarmer = $imageUploadWarmer;
        $this->imageRemover = $imageRemover;
        $this->folderChanger = $folderChanger;
        $this->groupBuilder = $groupBuilder;
        $this->pictureBuilder = $pictureBuilder;
    }

    /**
     * @param Trick $trick
     * @param TrickDTO $trickDTO
     *
     * @return Trick
     *
     * @throws \Exception
     */
    public function update(Trick $trick, TrickDTO $trickDTO)
    {
        $this->imageUploadWarmer->initialize('trick', $trickDTO->title);

        if ($trickDTO->mainPicture->file instanceof UploadedFile) {
            $mainPicture = $this->pictureBuilder->create($trickDTO->mainPicture, false, true);
            $this->imageRemover->addFileToRemove($trick->getMainPicture()->getWebPath());
        }

        if ($trickDTO->title !== $trick->getTitle()) {    // Change the pictures's folder
            $updatePictureInfo = $this->imageUploadWarmer->getUpdateImageInfo();
            $this->folderChanger->folderToChange($trick->getMainPicture()->getPath(), $updatePictureInfo['path']);

            $trick->getMainPicture()->update($updatePictureInfo['path'], 'main-'. $updatePictureInfo['alt']);
            foreach ($trick->getPictures() as $picture) {
                $picture->update($updatePictureInfo['path'], $updatePictureInfo['alt']);
            }
        }

        $trick->update(
            $trickDTO->title,
            $trickDTO->description,
            $mainPicture ?? $trick->getMainPicture(),
            $this->pictureCollectionUpdatePrepare->prepare($trick->getPictures()->toArray(), $trickDTO->pictures),
            $this->videoCollectionUpdatePrepare->prepare($trick->getVideos()->toArray(), $trickDTO->videos),
            $this->groupBuilder->create($trickDTO->groups, $trickDTO->newGroups)
        );

        return $trick;
    }
}
