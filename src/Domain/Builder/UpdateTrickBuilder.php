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
    private $pictureUpdatePrepare;

    /**
     * @var VideoCollectionUpdatePrepare
     */
    private $videoUpdatePrepare;

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
     * @param PictureCollectionUpdatePrepare $pictureUpdatePrepare
     * @param VideoCollectionUpdatePrepare $videoUpdatePrepare
     * @param ImageUploadWarmer $imageUploadWarmer
     * @param ImageRemover $imageRemover
     * @param FolderChanger $folderChanger
     * @param GroupBuilder $groupBuilder
     * @param PictureBuilder $pictureBuilder
     */
    public function __construct(
        PictureCollectionUpdatePrepare $pictureUpdatePrepare,
        VideoCollectionUpdatePrepare $videoUpdatePrepare,
        ImageUploadWarmer $imageUploadWarmer,
        ImageRemover $imageRemover,
        FolderChanger $folderChanger,
        GroupBuilder $groupBuilder,
        PictureBuilder $pictureBuilder
    ) {
        $this->pictureUpdatePrepare = $pictureUpdatePrepare;
        $this->videoUpdatePrepare = $videoUpdatePrepare;
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
            $this->pictureUpdatePrepare->prepare($trick->getPictures()->toArray(), $trickDTO->pictures),
            $this->videoUpdatePrepare->prepare($trick->getVideos()->toArray(), $trickDTO->videos),
            $this->groupBuilder->create($trickDTO->groups, $trickDTO->newGroups)
        );

        return $trick;
    }
}
