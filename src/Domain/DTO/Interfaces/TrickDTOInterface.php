<?php

namespace App\Domain\DTO\Interfaces;

use App\Domain\Entity\Picture;

/**
 * Interface TrickDTOInterface
 * @package App\Domain\DTO\Interfaces
 */
interface TrickDTOInterface
{
    /**
     * TrickDTOInterface constructor.
     * @param string $title
     */
    public function __construct(
        string $title,
        string $description,
        Picture $mainPicture,
        \ArrayAccess $pictures = null,
        \ArrayAccess $videos = null,
        \ArrayAccess $groups = null
    );
}
