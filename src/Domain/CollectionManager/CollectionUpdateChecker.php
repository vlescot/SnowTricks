<?php
declare(strict_types = 1);

namespace App\Domain\CollectionManager;

use App\Domain\DTO\Interfaces\PictureDTOInterface;
use App\Domain\DTO\Interfaces\VideoDTOInterface;
use App\Domain\Entity\Interfaces\PictureInterface;
use App\Domain\Entity\Interfaces\VideoInterface;
use App\Domain\CollectionManager\Interfaces\CollectionCheckerInterface;

/**
 * Class PictureCollectionChecker
 * @package App\Domain\CollectionChecker
 *
 *
 * This class compare the $collectionDTO array that contains PictureDTO or VideoDTO instance from the form
 * with the $collection array that contains Picture entity from the Trick Entity
 * and sort each DTO to be a new Object
 * and sort each Entity to be a old Object
 */
final class CollectionUpdateChecker implements CollectionCheckerInterface
{
    /**
     * @var array
     */
    private $newObjects = [];

    /**
     * @var array
     */
    private $deletedObjects = [];


    /**
     * @param PictureInterface $entity
     * @param PictureDTOInterface $dto
     *
     * @return bool
     */
    private function checkIfDifferentPicture(PictureInterface $entity, PictureDTOInterface $dto)
    {
        $dto->file->getFilename() !== $entity->getFilename()
            ? $result = true
            : $result = false;

        return $result;
    }

    /**
     * @param VideoInterface $entity
     * @param VideoDTOInterface $dto
     *
     * @return bool
     */
    private function checkIfDifferentVideo(VideoInterface $entity, VideoDTOInterface $dto)
    {
        $dto->iFrame !== $entity->getIFrame()
            ? $result = true
            : $result = false;

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function compare(array $collection, array $collectionDTO, string $className): void
    {
        $checkIfDifferent = 'checkIfDifferent'. $className;

        foreach ($collectionDTO as $key => $dto) {
            if (array_key_exists($key, $collection)) {
                if ($this->$checkIfDifferent($collection[$key], $dto)) {
                    $this->deletedObjects[$key] = $collection[$key];
                    $this->newObjects[$key] = $dto;
                }
            } else {
                $this->newObjects[$key] = $dto;
            }
        }

        foreach ($collection as $key => $entity) {
            if (!array_key_exists($key, $collectionDTO)) {
                $this->deletedObjects[$key] = $entity;
            }
        }
    }


    /**
     * {@inheritdoc}
     */
    public function getNewObjects(): array
    {
        $newObjects = $this->newObjects;
        $this->newObjects = [];

        return $newObjects;
    }

    /**
     * {@inheritdoc}
     */
    public function getDeletedObjects(): array
    {
        $deletedObjects = $this->deletedObjects;
        $this->deletedObjects = [];

        return $deletedObjects;
    }
}
