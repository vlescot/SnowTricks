<?php
declare(strict_types = 1);

namespace App\Domain\Builder;

use App\Domain\Builder\Interfaces\VideoBuilderInterface;
use App\Domain\DTO\Interfaces\VideoDTOInterface;
use App\Domain\Entity\Interfaces\VideoInterface;
use App\Domain\Entity\Video;

final class VideoBuilder implements VideoBuilderInterface
{
    /**
     * @param $videosDTO
     * @param bool $isCollection
     *
     * @return \App\Domain\Entity\Interfaces\VideoInterface|Video|array
     *
     * @throws \Exception
     */
    public function create($videosDTO, bool $isCollection)
    {
        if ($isCollection) {
            $videos = [];
            foreach ($videosDTO as $videoDTO) {
                $videos[] = $this->createEntity($videoDTO);
            }
            return $videos;
        }

        return $this->createEntity($videosDTO);
    }

    /**
     * @param VideoDTOInterface $videoDTO
     *
     * @return VideoInterface
     *
     * @throws \Exception
     */
    public function createEntity(VideoDTOInterface $videoDTO): VideoInterface
    {
        return new Video($videoDTO->iFrame);
    }
}
