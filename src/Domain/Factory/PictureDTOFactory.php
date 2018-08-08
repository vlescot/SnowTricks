<?php
declare(strict_types = 1);

namespace App\Domain\Factory;

use App\Domain\DTO\PictureDTO;
use App\Domain\Entity\Picture;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class PictureDTOFactory
 * @package App\Form\Factory
 */
class PictureDTOFactory
{
    /**
     * @param Picture $picture
     * @return PictureDTO
     */
    public function create(Picture $picture) :PictureDTO
    {
        return new PictureDTO(new File('.' . $picture->getWebPath()));
    }
}
