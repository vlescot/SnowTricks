<?php
declare(strict_types=1);

namespace App\Domain\Factory\Interfaces;

use App\Domain\DTO\Interfaces\VideoDTOInterface;
use App\Domain\Entity\Interfaces\VideoInterface;

interface VideoDTOFactoryInterface
{
    /**
     * @param VideoInterface $video
     *
     * @return VideoDTOInterface
     */
    public function create(VideoInterface $video) :VideoDTOInterface;
}
