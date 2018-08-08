<?php
declare(strict_types = 1);

namespace App\Domain\CollectionManager\CollectionUpdatePrepare;

use App\Domain\Builder\PictureBuilder;
use App\Domain\CollectionManager\CollectionChecker\PictureCollectionChecker;
use App\Service\Image\ImageRemover;

class PictureCollectionUpdatePrepare
{
    /**
     * @var PictureCollectionChecker
     */
    private $pictureCollectionChecker;

    /**
     * @var ImageRemover
     */
    private $imageRemover;

    /**
     * @var PictureBuilder
     */
    private $createPictureBuilder;

    /**
     * PictureCollectionLeader constructor.
     *
     * @param PictureCollectionChecker $pictureCollectionChecker
     * @param ImageRemover $imageRemover
     * @param PictureBuilder $createPictureBuilder
     */
    public function __construct(
        PictureCollectionChecker $pictureCollectionChecker,
        ImageRemover $imageRemover,
        PictureBuilder $createPictureBuilder
    ) {
        $this->pictureCollectionChecker = $pictureCollectionChecker;
        $this->imageRemover = $imageRemover;
        $this->createPictureBuilder = $createPictureBuilder;
    }

    /**
     * @param array $pictures
     * @param array $picturesDTO
     *
     * @return array
     *
     * @throws \Exception
     */
    public function prepare(array $pictures, array $picturesDTO)
    {
        $this->pictureCollectionChecker->compare($pictures, $picturesDTO);

        foreach ($this->pictureCollectionChecker->getDeletedObject() as $key => $picture) {
            $this->imageRemover->addFileToRemove($picture->getWebPath());
            unset($pictures[$key]);
        }

        foreach ($this->pictureCollectionChecker->getDirtyObject() as $key => $pictureDTO) {
            $this->imageRemover->addFileToRemove($pictures[$key]->getWebPath());
            $pictures[$key] = $this->createPictureBuilder->create($pictureDTO, false);
        }

        foreach ($this->pictureCollectionChecker->getNewObject() as $key => $pictureDTO) {
            $pictures[] = $this->createPictureBuilder->create($pictureDTO, false);
        }

        return $pictures;
    }
}
