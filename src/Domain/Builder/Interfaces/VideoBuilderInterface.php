<?php
declare(strict_types=1);

namespace App\Domain\Builder\Interfaces;

use App\Domain\DTO\Interfaces\VideoDTOInterface;
use App\Domain\Entity\Interfaces\VideoInterface;

interface VideoBuilderInterface
{
    /**
     * @param $videosDTO
     * @param bool $isCollection
     *
     * @return VideoInterface
     */
    public function create($videosDTO, bool $isCollection);

    /**
     * @param VideoDTOInterface $videoDTO
     *
     * @return VideoInterface
     */
    public function createEntity(VideoDTOInterface $videoDTO): VideoInterface;
}
