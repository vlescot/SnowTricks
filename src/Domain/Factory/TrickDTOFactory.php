<?php
declare(strict_types = 1);

namespace App\Domain\Factory;

use App\Domain\DTO\Interfaces\TrickDTOInterface;
use App\Domain\DTO\TrickDTO;
use App\Domain\Entity\Interfaces\TrickInterface;
use App\Domain\Entity\Trick;
use App\Domain\Factory\Interfaces\PictureDTOFactoryInterface;
use App\Domain\Factory\Interfaces\TrickDTOFactoryInterface;
use App\Domain\Factory\Interfaces\VideoDTOFactoryInterface;

/**
 * Class TrickDTOFactory
 * @package App\Form\Factory
 */
final class TrickDTOFactory implements TrickDTOFactoryInterface
{
    /**
     * @var PictureDTOFactory
     */
    private $pictureDTOFactory;

    /**
     * @var VideoDTOFactory
     */
    private $videoDTOFactory;

    /**
     * @inheritdoc
     */
    public function __construct(
        PictureDTOFactoryInterface $pictureDTOFactory,
        VideoDTOFactoryInterface $videoDTOFactory
    ) {
        $this->pictureDTOFactory = $pictureDTOFactory;
        $this->videoDTOFactory = $videoDTOFactory;
    }

    /**
     * @inheritdoc
     */
    public function create(TrickInterface $trick): TrickDTOInterface
    {
        $mainPictureDTO = $this->pictureDTOFactory->create($trick->getMainPicture());

        $pictures = $trick->getPictures()->toArray();
        $picturesDTO = [];
        foreach ($pictures as $picture) {
            $picturesDTO [] = $this->pictureDTOFactory->create($picture);
        }

        $videos = $trick->getVideos()->toArray();
        $videosDTO = [];
        foreach ($videos as $video) {
            $videosDTO [] = $this->videoDTOFactory->create($video);
        }

        return new TrickDTO(
            $trick->getTitle(),
            $trick->getDescription(),
            $mainPictureDTO,
            $picturesDTO,
            $videosDTO,
            $trick->getGroups(),
            null
        );
    }
}
