<?php
declare(strict_types = 1);

namespace App\Domain\Factory;

use App\Domain\DTO\VideoDTO;
use App\Domain\Entity\Video;

/**
 * Class VideoDTOFactory
 * @package App\Form\Factory
 */
class VideoDTOFactory
{
    /**
     * @param Video $video
     * @return VideoDTO
     */
    public function create(Video $video) :VideoDTO
    {
        return new VideoDTO($video->getIFrame());
    }
}
