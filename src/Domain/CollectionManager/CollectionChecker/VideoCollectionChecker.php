<?php
declare(strict_types = 1);

namespace App\Domain\CollectionManager\CollectionChecker;

/**
 * Class VideoCollectionChecker
 * @package App\Domain\CollectionChecker
 */
class VideoCollectionChecker
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
     * @param array $videos
     * @param array $videosDTO
     */
    public function compare(array $videos, array $videosDTO)
    {
        foreach ($videosDTO as $key => $videoDTO) {
            if (array_key_exists($key, $videos)) {
                if ($videoDTO->iFrame !== $videos[$key]->getIFrame()) {
                    $this->dirtyObjects[$key] = $videoDTO;
                }
            } else {
                $this->newObjects[] = $videoDTO;
            }
        }

        foreach ($videos as $key => $video) {
            if (!array_key_exists($key, $videosDTO)) {
                $this->deletedObjects[$key] = $video;
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
