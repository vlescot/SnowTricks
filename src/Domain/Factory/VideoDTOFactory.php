<?php
declare(strict_types = 1);

namespace App\Domain\Factory;

use App\Domain\DTO\Interfaces\VideoDTOInterface;
use App\Domain\DTO\VideoDTO;
use App\Domain\Entity\Interfaces\VideoInterface;
use App\Domain\Factory\Interfaces\VideoDTOFactoryInterface;

/**
 * Class VideoDTOFactory
 * @package App\Form\Factory
 */
final class VideoDTOFactory implements VideoDTOFactoryInterface
{
    /**
     * @inheritdoc
     */
    public function create(VideoInterface $video) :VideoDTOInterface
    {
        return new VideoDTO($video->getIFrame());
    }
}
