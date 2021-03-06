<?php
declare(strict_types = 1);

namespace App\Domain\CollectionManager;

use App\Domain\Builder\Interfaces\PictureBuilderInterface;
use App\Domain\Builder\Interfaces\VideoBuilderInterface;
use App\Domain\DTO\Interfaces\PictureDTOInterface;
use App\Domain\Entity\Interfaces\PictureInterface;
use App\Domain\CollectionManager\Interfaces\CollectionCheckerInterface;
use App\Domain\CollectionManager\Interfaces\CollectionUpdatePrepareInterface;
use App\App\Image\Interfaces\ImageRemoverInterface;

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
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    private function getClassName(array $collection)
    {
        $class = reset($collection);

        if ($class instanceof PictureInterface || $class instanceof PictureDTOInterface) {
            return 'Picture';
        }
        return 'Video';
    }

    /**
     * {@inheritdoc}
     */
    public function prepare(array $collection, array $collectionDTO): array
    {
        if (!empty($collection)) {
            $className = $this->getClassName($collection);
        } else {
            $className = $this->getClassName($collectionDTO);
        }

        $this->collectionChecker->compare($collection, $collectionDTO, $className);

        foreach ($this->collectionChecker->getDeletedObjects() as $key => $entity) {
            unset($collection[$key]);

            if ($entity instanceof PictureInterface) {
                $this->imageRemover->addFileToRemove($entity);
            }
        }

        if ($className === 'Picture') {
            foreach ($this->collectionChecker->getNewObjects() as $key => $dto) {
                $collection[$key] = $this->pictureBuilder->create($dto, false);
            }
        } elseif ($className === 'Video') {
            foreach ($this->collectionChecker->getNewObjects() as $key => $dto) {
                $collection[$key] = $this->videoBuilder->create($dto, false);
            }
        }

        return $collection;
    }
}
