<?php

namespace App\Domain\Entity;

use App\Domain\DTO\TrickDTO;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class Trick.
 */
class Trick
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $createdAt;

    /**
     * @var string
     */
    private $updatedAt;

    /**
     * @var User
     */
    private $author;

    /**
     * @var boolean
     */
    private $validated;

    /**
     * @var Picture
     */
    private $mainPicture;

    /**
     * @var \ArrayAccess
     */
    private $comments;

    /**
     * @var \ArrayAccess
     */
    private $pictures;

    /**
     * @var \ArrayAccess
     */
    private $videos;

    /**
     * @var \ArrayAccess
     */
    private $groups;

    /**
     * Trick constructor.
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->createdAt = time();
        $this->updatedAt = time();

        $this->comments = new ArrayCollection();
        $this->pictures = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->groups = new ArrayCollection();
    }

    /**
     * @param TrickDTO $trickDTO
     */
    public function creation(TrickDTO $trickDTO)
    {
        $this->title = $trickDTO->title;
        $this->slug = $trickDTO->slug;
        $this->description = $trickDTO->description;
        $this->validated = $trickDTO->validated;
        $this->author = $trickDTO->author;
        $this->mainPicture = $trickDTO->mainPicture;

        if ($trickDTO->pictures) {
            $this->pictures = $trickDTO->pictures;
        }

        if ($trickDTO->videos) {
            $this->videos = $trickDTO->videos;
        }

        if ($trickDTO->groups) {
            foreach ($trickDTO->groups->getIterator() as $group) {
                $this->addGroup($group);
            }
        }
    }


    /**
     * @param Comment $comment
     *
     * @return Trick
     */
    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
        }

        return $this;
    }

    /**
     * @param Comment $comment
     *
     * @return Trick
     */
    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
        }

        return $this;
    }

    /**
     * @param Picture $picture
     *
     * @return Trick
     */
    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
        }

        return $this;
    }

    /**
     * @param Picture $picture
     *
     * @return Trick
     */
    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->contains($picture)) {
            $this->pictures->removeElement($picture);
        }

        return $this;
    }

    /**
     * @param Video $video
     *
     * @return Trick
     */
    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
        }

        return $this;
    }

    /**
     * @param Video $video
     *
     * @return Trick
     */
    public function removeVideo(Video $video): self
    {
        if ($this->videos->contains($video)) {
            $this->videos->removeElement($video);
        }

        return $this;
    }

    /**
     * @param Group $group
     *
     * @return Trick
     */
    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
            $group->addTrick($this);
        }

        return $this;
    }

    /**
     * @param Group $group
     *
     * @return Trick
     */
    public function removeGroup(Group $group): self
    {
        if ($this->groups->contains($group)) {
            $this->groups->removeElement($group);
            $group->removeTrick($this);
        }

        return $this;
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @return bool
     */
    public function isValidated(): bool
    {
        return $this->validated;
    }

    /**
     * @return Picture
     */
    public function getMainPicture(): Picture
    {
        return $this->mainPicture;
    }

    /**
     * @return \ArrayAccess
     */
    public function getComments(): \ArrayAccess
    {
        return $this->comments;
    }

    /**
     * @return \ArrayAccess
     */
    public function getPictures(): \ArrayAccess
    {
        return $this->pictures;
    }

    /**
     * @return \ArrayAccess
     */
    public function getVideos(): \ArrayAccess
    {
        return $this->videos;
    }

    /**
     * @return \ArrayAccess
     */
    public function getGroups(): \ArrayAccess
    {
        return $this->groups;
    }
}
