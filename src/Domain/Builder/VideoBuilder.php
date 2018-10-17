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
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function createEntity(VideoDTOInterface $videoDTO): VideoInterface
    {
        return new Video($videoDTO->iFrame);
    }
}
