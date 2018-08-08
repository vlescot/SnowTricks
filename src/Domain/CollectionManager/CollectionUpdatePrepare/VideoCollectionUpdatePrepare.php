<?php
declare(strict_types = 1);

namespace App\Domain\CollectionManager\CollectionUpdatePrepare;

use App\Domain\Builder\VideoBuilder;
use App\Domain\CollectionManager\CollectionChecker\VideoCollectionChecker;

class VideoCollectionUpdatePrepare
{
    /**
     * @var VideoCollectionChecker
     */
    private $videoCollectionChecker;

    /**
     * @var VideoBuilder
     */
    private $createVideoBuilder;

    /**
     * VideoCollectionLeader constructor.
     *
     * @param VideoCollectionChecker $videoCollectionChecker
     * @param VideoBuilder $createVideoBuilder
     */
    public function __construct(
        VideoCollectionChecker $videoCollectionChecker,
        VideoBuilder $createVideoBuilder
    ) {
        $this->videoCollectionChecker = $videoCollectionChecker;
        $this->createVideoBuilder = $createVideoBuilder;
    }

    /**
     * @param array $videos
     * @param array $videosDTO
     * @return array
     */
    public function prepare(array $videos, array $videosDTO)
    {
        $this->videoCollectionChecker->compare($videos, $videosDTO);

        foreach ($this->videoCollectionChecker->getDeletedObject() as $key => $video) {
            unset($videos[$key]);
        }

        foreach ($this->videoCollectionChecker->getDirtyObject() as $key => $videoDTO) {
            $videos[$key] = $this->createVideoBuilder->create($videoDTO, false);
        }

        foreach ($this->videoCollectionChecker->getNewObject() as $key => $videoDTO) {
            $videos[] = $this->createVideoBuilder->create($videoDTO, false);
        }

        return $videos;
    }
}
