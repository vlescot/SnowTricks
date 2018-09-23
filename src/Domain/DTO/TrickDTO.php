<?php
declare(strict_types = 1);

namespace App\Domain\DTO;

use App\Domain\DTO\Interfaces\PictureDTOInterface;
use App\Domain\DTO\Interfaces\TrickDTOInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class TrickDTO.
 */
final class TrickDTO implements TrickDTOInterface
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
     * @var PictureDTOInterface
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
     * {@inheritdoc}
     */
    public function __construct(
        string $title,
        string $description,
        PictureDTOInterface $mainPicture,
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
