<?php
declare(strict_types = 1);

namespace App\Domain\CollectionManager\CollectionUpdatePrepare;

use App\Domain\Builder\PictureBuilder;
use App\Domain\CollectionManager\CollectionChecker\PictureCollectionChecker;
use App\Domain\Repository\PictureRepository;
use App\UI\Service\Image\ImageRemover;

class PictureCollectionUpdatePrepare
{
    /**
     * @var PictureCollectionChecker
     */
    private $pictureChecker;

    /**
     * @var ImageRemover
     */
    private $imageRemover;

    /**
     * @var PictureBuilder
     */
    private $createPictureBuilder;

    /**
     * PictureCollectionUpdatePrepare constructor.
     *
     * @param PictureCollectionChecker $pictureChecker
     * @param ImageRemover $imageRemover
     * @param PictureBuilder $createPictureBuilder
     */
    public function __construct(
        PictureCollectionChecker $pictureChecker,
        ImageRemover $imageRemover,
        PictureBuilder $createPictureBuilder
    ) {
        $this->pictureChecker = $pictureChecker;
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
        $this->pictureChecker->compare($pictures, $picturesDTO);

        foreach ($this->pictureChecker->getDeletedObject() as $key => $picture) {
            $this->imageRemover->addFileToRemove($picture);
            unset($pictures[$key]);
        }

        foreach ($this->pictureChecker->getDirtyObject() as $key => $pictureDTO) {
            $pictures[$key] = $this->createPictureBuilder->create($pictureDTO, false);
        }

        foreach ($this->pictureChecker->getNewObject() as $key => $pictureDTO) {
            $pictures[] = $this->createPictureBuilder->create($pictureDTO, false);
        }

        return $pictures;
    }
}
