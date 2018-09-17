<?php
declare(strict_types = 1);

namespace App\Domain\Factory;

use App\Domain\DTO\Interfaces\PictureDTOInterface;
use App\Domain\DTO\PictureDTO;
use App\Domain\Entity\Interfaces\PictureInterface;
use App\Domain\Factory\Interfaces\PictureDTOFactoryInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class PictureDTOFactory
 * @package App\Form\Factory
 */
final class PictureDTOFactory implements PictureDTOFactoryInterface
{
    /**
     * @inheritdoc
     */
    public function create(PictureInterface $picture) :PictureDTOInterface
    {
        return new PictureDTO(new File('.' . $picture->getWebPath(), false));
    }
}
