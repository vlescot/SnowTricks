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
    private $videoChecker;

    /**
     * @var VideoBuilder
     */
    private $createVideoBuilder;

    /**
     * VideoCollectionLeader constructor.
     *
     * @param VideoCollectionChecker $videoChecker
     * @param VideoBuilder $createVideoBuilder
     */
    public function __construct(
        VideoCollectionChecker $videoChecker,
        VideoBuilder $createVideoBuilder
    ) {
        $this->videoChecker = $videoChecker;
        $this->createVideoBuilder = $createVideoBuilder;
    }

    /**
     * @param array $videos
     * @param array $videosDTO
     * @return array
     */
    public function prepare(array $videos, array $videosDTO)
    {
        $this->videoChecker->compare($videos, $videosDTO);

        foreach ($this->videoChecker->getDeletedObject() as $key => $video) {
            unset($videos[$key]);
        }

        foreach ($this->videoChecker->getDirtyObject() as $key => $videoDTO) {
            $videos[$key] = $this->createVideoBuilder->create($videoDTO, false);
        }

        foreach ($this->videoChecker->getNewObject() as $videoDTO) {
            $videos[] = $this->createVideoBuilder->create($videoDTO, false);
        }

        return $videos;
    }
}
