<?php
declare(strict_types=1);

namespace App\Domain\Factory\Interfaces;

use App\Domain\DTO\Interfaces\PictureDTOInterface;
use App\Domain\Entity\Interfaces\PictureInterface;

interface PictureDTOFactoryInterface
{
    /**
     * @param PictureInterface $picture
     * @return PictureDTOInterface
     */
    public function create(PictureInterface $picture) :PictureDTOInterface;
}
