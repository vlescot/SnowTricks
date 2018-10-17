<?php
declare(strict_types=1);

namespace App\Domain\Factory\Interfaces;

use App\Domain\DTO\Interfaces\TrickDTOInterface;
use App\Domain\Entity\Interfaces\TrickInterface;

interface TrickDTOFactoryInterface
{

    /**
     * TrickDTOFactory constructor.
     *
     * @param PictureDTOFactoryInterface $pictureDTOFactory
     * @param VideoDTOFactoryInterface $videoDTOFactory
     */
    public function __construct(
        PictureDTOFactoryInterface $pictureDTOFactory,
        VideoDTOFactoryInterface $videoDTOFactory
    );

    /**
     * @param TrickInterface $trick
     *
     * @return TrickDTOInterface
     */
    public function create(TrickInterface $trick): TrickDTOInterface;
}
