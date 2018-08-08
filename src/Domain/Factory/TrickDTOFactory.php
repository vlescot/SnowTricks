<?php
declare(strict_types = 1);

namespace App\Domain\Factory;

use App\Domain\DTO\TrickDTO;
use App\Domain\Entity\Trick;

/**
 * Class TrickDTOFactory
 * @package App\Form\Factory
 */
class TrickDTOFactory
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
     * TrickDTOFactory constructor.
     *
     * @param PictureDTOFactory $pictureDTOFactory
     * @param VideoDTOFactory $videoDTOFactory
     */
    public function __construct(PictureDTOFactory $pictureDTOFactory, VideoDTOFactory $videoDTOFactory)
    {
        $this->pictureDTOFactory = $pictureDTOFactory;
        $this->videoDTOFactory = $videoDTOFactory;
    }

    /**
     * @param Trick $trick
     * @return TrickDTO
     */
    public function create(Trick $trick): TrickDTO
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

        return $trickDTO = new TrickDTO(
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
