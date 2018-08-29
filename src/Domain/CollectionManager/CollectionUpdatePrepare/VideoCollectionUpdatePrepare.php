<?php
declare(strict_types = 1);

namespace App\Domain\CollectionManager\CollectionUpdatePrepare;

use App\Domain\Builder\VideoBuilder;
use App\Domain\CollectionManager\CollectionChecker\VideoCollectionChecker;
use App\Domain\Repository\VideoRepository;

class VideoCollectionUpdatePrepare
{
    /**
     * @var VideoCollectionChecker
     */
    private $videoChecker;

    /**
     * @var VideoBuilder
     */
    private $videoBuilder;

    /**
     * @var VideoRepository
     */
    private $videoRepository;

    /**
     * VideoCollectionUpdatePrepare constructor.
     *
     * @param VideoCollectionChecker $videoChecker
     * @param VideoBuilder $videoBuilder
     * @param VideoRepository $videoRepository
     */
    public function __construct(
        VideoCollectionChecker $videoChecker,
        VideoBuilder $videoBuilder,
        VideoRepository $videoRepository
    ) {
        $this->videoChecker = $videoChecker;
        $this->videoBuilder = $videoBuilder;
        $this->videoRepository = $videoRepository;
    }


    /**
     * @param array $videos
     * @param array $videosDTO
     *
     * @return array
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function prepare(array $videos, array $videosDTO)
    {
        $this->videoChecker->compare($videos, $videosDTO);

        foreach ($this->videoChecker->getDeletedObject() as $key => $video) {
            $this->videoRepository->remove($videos[$key]);
            unset($videos[$key]);
        }

        foreach ($this->videoChecker->getDirtyObject() as $key => $videoDTO) {
            $videos[$key] = $this->videoBuilder->create($videoDTO, false);
        }

        foreach ($this->videoChecker->getNewObject() as $videoDTO) {
            $videos[] = $this->videoBuilder->create($videoDTO, false);
        }

        return $videos;
    }
}
