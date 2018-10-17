<?php
declare(strict_types=1);

namespace App\Domain\DTO\Interfaces;

interface TrickDTOInterface
{
    /**
     * TrickDTO constructor.
     *
     * @param string $title
     * @param string $description
     * @param PictureDTOInterface $mainPicture
     * @param array|null $pictures
     * @param array|null $videos
     * @param \ArrayAccess|null $groups
     * @param array|null $newGroups
     */
    public function __construct(
        string $title,
        string $description,
        PictureDTOInterface $mainPicture,
        array $pictures = null,
        array $videos = null,
        \ArrayAccess $groups = null,
        array $newGroups = null
    );
}
