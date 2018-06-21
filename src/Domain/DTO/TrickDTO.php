<?php

namespace App\Domain\DTO;

use App\Domain\Entity\Picture;
use App\Domain\Entity\User;

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
    public $slug;

    /**
     * @var string
     */
    public $description;

    /**
     * @var \DateTime;
     */
    public $createdAt;

    /**
     * @var \DateTime
     */
    public $updatedAt;

    /**
     * @var User
     */
    public $author;

    /**
     * @var boolean
     */
    public $validated;

    /**
     * @var Picture
     */
    public $mainPicture;

    /**
     * @var \ArrayAccess
     */
    public $comments;

    /**
     * @var \ArrayAccess
     */
    public $pictures;

    /**
     * @var \ArrayAccess
     */
    public $videos;

    /**
     * @var \ArrayAccess
     */
    public $groups;

    /**
     * TrickDTO constructor.
     * @param string $title
     * @param string $slug
     * @param string $description
     * @param bool $validated
     * @param User $author
     * @param Picture $mainPicture
     * @param \ArrayAccess|null $comments
     * @param \ArrayAccess|null $pictures
     * @param \ArrayAccess|null $videos
     * @param \ArrayAccess|null $groups
     */
    public function __construct(
        string $title,
        string $slug,
        string $description,
        bool $validated,
        User $author,
        Picture $mainPicture,
        \ArrayAccess $comments = null,
        \ArrayAccess $pictures = null,
        \ArrayAccess $videos = null,
        \ArrayAccess $groups = null
    ) {
        $this->title = $title;
        $this->slug = $slug;
        $this->description = $description;
        $this->validated = $validated;
        $this->author = $author;
        $this->mainPicture = $mainPicture;
        $this->comments = $comments;
        $this->pictures = $pictures;
        $this->videos = $videos;
        $this->groups = $groups;
    }
}
