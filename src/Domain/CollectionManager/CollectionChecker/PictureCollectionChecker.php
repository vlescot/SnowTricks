<?php
declare(strict_types = 1);

namespace App\Domain\CollectionManager\CollectionChecker;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class PictureCollectionChecker
 * @package App\Domain\CollectionChecker
 */
class PictureCollectionChecker
{
    /**
     * @var array
     */
    private $dirtyObjects = [];

    /**
     * @var array
     */
    private $newObjects = [];

    /**
     * @var array
     */
    private $deletedObjects = [];

    /**
     * @param array $pictures
     * @param array $picturesDTO
     */
    public function compare(array $pictures, array $picturesDTO)
    {
        foreach ($picturesDTO as $key => $pictureDTO) {
            if ($pictureDTO->file instanceof UploadedFile) {
                if (array_key_exists($key, $pictures)) {
                    $this->dirtyObjects[$key] = $pictureDTO;
                } else {
                    $this->newObjects[] = $pictureDTO;
                }
            }
        }

        foreach ($pictures as $key => $picture) {
            if (!array_key_exists($key, $picturesDTO)) {
                $this->deletedObjects[$key] = $picture;
            }
        }
    }

    /**
     * @return array
     */
    public function getDirtyObject()
    {
        return $this->dirtyObjects;
    }

    /**
     * @return array
     */
    public function getNewObject()
    {
        return $this->newObjects;
    }

    /**
     * @return array
     */
    public function getDeletedObject()
    {
        return $this->deletedObjects;
    }
}
