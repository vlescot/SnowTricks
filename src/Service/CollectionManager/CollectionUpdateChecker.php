<?php
declare(strict_types = 1);

namespace App\Service\CollectionManager;

use App\Domain\Entity\Interfaces\PictureInterface;
use App\Domain\Entity\Interfaces\VideoInterface;
use App\Service\CollectionManager\Interfaces\CollectionCheckerInterface;

/**
 * Class PictureCollectionChecker
 * @package App\Domain\CollectionChecker
 *
 * TODO
 * This class compare the $collectionDTO array that contains PictureDTO entity from the form
 * with the $collection array that contains Picture entity from the Trick Entity
 * and sort each PictureDTO to be a new Entity
 * and sort each Picture to be a old Entity
 */
final class CollectionUpdateChecker implements CollectionCheckerInterface
{
    /**
     * @var string
     */
    private $entityClass;

    /**
     * @var array
     */
    private $newObjects = [];

    /**
     * @var array
     */
    private $deletedObjects = [];


    /**
     * @param array $collection
     */
    private function getClassName(array $collection)
    {
        if (reset($collection) instanceof PictureInterface) {
            $this->entityClass = 'Picture';
        } elseif (reset($collection) instanceof VideoInterface) {
            $this->entityClass = 'Video';
        }
    }

    /**
     * @param string $objectName
     * @param $entity
     * @param $dto
     *
     * @return bool
     */
    private function checkIfDifferent($entity, $dto)
    {
        switch ($this->entityClass) {
            case 'Picture':
                $dto->file->getFilename() !== $entity->getFilename()
                    ? $result = true
                    : $result = false;
                break;
            case 'Video':
                $dto->iFrame !== $entity->getIFrame()
                    ? $result = true
                    : $result = false;
                break;
        }

        return $result;
    }


    /**
     * @param array $collection
     * @param array $collectionDTO
     */
    public function compare(array $collection, array $collectionDTO): void
    {
        $this->className = $this->getClassName($collection);

        foreach ($collectionDTO as $key => $dto) {
            if (array_key_exists($key, $collection)) {
                if ($this->checkIfDifferent($collection[$key], $dto)) {
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
     * @return array
     */
    public function getNewObjects(): array
    {
        $newObjects = $this->newObjects;
        $this->newObjects = [];

        return $newObjects;
    }

    /**
     * @return array
     */
    public function getDeletedObjects(): array
    {
        $deletedObjects = $this->deletedObjects;
        $this->deletedObjects = [];

        return $deletedObjects;
    }
}
