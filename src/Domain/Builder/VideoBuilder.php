<?php
declare(strict_types = 1);

namespace App\Domain\Builder;

use App\Domain\DTO\VideoDTO;
use App\Domain\Entity\Video;

class VideoBuilder
{
    /**
     * @param $videosDTO
     * @param bool $isCollection
     * @return Video|array
     */
    public function create($videosDTO, bool $isCollection)
    {
        if ($isCollection) {
            $videos = [];
            foreach ($videosDTO as $videoDTO) {
                $videos[] = $this->createEntity($videoDTO);
            }
            return $videos;
        } else {
            return $this->createEntity($videosDTO);
        }
    }

    public function createEntity(VideoDTO $videoDTO)
    {
        return new Video($videoDTO->iFrame);
    }
}
