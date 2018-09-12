<?php
declare(strict_types = 1);

namespace App\Service\CollectionManager;

use App\Domain\Builder\Interfaces\PictureBuilderInterface;
use App\Domain\Builder\Interfaces\VideoBuilderInterface;
use App\Domain\Entity\Interfaces\PictureInterface;
use App\Service\CollectionManager\Interfaces\CollectionCheckerInterface;
use App\Service\CollectionManager\Interfaces\CollectionUpdatePrepareInterface;
use App\Service\Image\Interfaces\ImageRemoverInterface;

final class CollectionUpdatePrepare implements CollectionUpdatePrepareInterface
{
    /**
     * @var CollectionCheckerInterface
     */
    private $collectionChecker;

    /**
     * @var ImageRemoverInterface
     */
    private $imageRemover;

    /**
     * @var PictureBuilderInterface
     */
    private $pictureBuilder;

    /**
     * @var VideoBuilderInterface
     */
    private $videoBuilder;

    /**
     * CollectionUpdatePrepare constructor.
     *
     * @param CollectionCheckerInterface $collectionChecker
     * @param ImageRemoverInterface $imageRemover
     * @param PictureBuilderInterface $pictureBuilder
     * @param VideoBuilderInterface $videoBuilder
     */
    public function __construct(
        CollectionCheckerInterface $collectionChecker,
        ImageRemoverInterface $imageRemover,
        PictureBuilderInterface $pictureBuilder,
        VideoBuilderInterface $videoBuilder
    ) {
        $this->collectionChecker = $collectionChecker;
        $this->imageRemover = $imageRemover;
        $this->pictureBuilder = $pictureBuilder;
        $this->videoBuilder = $videoBuilder;
    }


    /**
     * @param array $collection
     * @param array $collectionDTO
     *
     * @return array
     */
    public function prepare(array $collection, array $collectionDTO): array
    {
        $this->collectionChecker->compare($collection, $collectionDTO);

        foreach ($this->collectionChecker->getDeletedObjects() as $key => $entity) {
            unset($collection[$key]);

            if ($entity instanceof PictureInterface) {
                $this->imageRemover->addFileToRemove($entity);
            }
        }

        foreach ($this->collectionChecker->getNewObjects() as $key => $dto) {
            $collection[$key] = $this->pictureBuilder->create($dto, false);
        }

        return $collection;
    }
}
