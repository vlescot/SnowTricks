<?php
declare(strict_types = 1);

namespace App\Domain\DTO;

use App\Domain\Entity\Picture;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class TrickDTO.
 */
class TrickDTO
{
    /**
     * @var string
     */
    public $title;
    /**
     * @var string
     */
    public $description;
    /**
     * @var PictureDTO|Picture
     */
    public $mainPicture;
    /**
     * @var array|null
     */
    public $pictures;
    /**
     * @var array|null
     */
    public $videos;
    /**
     * @var \ArrayAccess|null
     */
    public $groups;
    /**
     * @var array
     */
    public $newGroups;

    /**
     * TrickDTO constructor.
     * @param string $title
     * @param string $description
     * @param PictureDTO $mainPicture
     * @param array|null $pictures
     * @param array|null $videos
     * @param \ArrayAccess|null $groups
     * @param array|null $newGroups
     */
    public function __construct(
        string $title,
        string $description,
        PictureDTO $mainPicture,
        array $pictures = null,
        array $videos = null,
        \ArrayAccess $groups = null,
        array $newGroups = null
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->mainPicture = $mainPicture;
        $this->pictures = $pictures;
        $this->videos = $videos;
        $this->groups = $groups ?? new ArrayCollection();
        $this->newGroups = $newGroups;
    }
}
