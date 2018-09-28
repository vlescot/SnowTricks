<?php
declare(strict_types = 1);

namespace App\Domain\Entity;

use App\Domain\Entity\Interfaces\CommentInterface;
use App\Domain\Entity\Interfaces\GroupInterface;
use App\Domain\Entity\Interfaces\PictureInterface;
use App\Domain\Entity\Interfaces\TrickInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Trick.
 */
class Trick implements TrickInterface
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
     * @var Picture
     */
    private $mainPicture;

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
     * @var \ArrayAccess
     */
    private $comments;


    /**
     * @inheritdoc
     */
    public function __construct(
        string $title,
        string $description,
        UserInterface $author,
        PictureInterface $mainPicture,
        array $pictures = [],
        array $videos = [],
        \ArrayAccess $groups = null
    ) {
        $this->id = Uuid::uuid4();
        $this->title = $title;
        $this->slug = strtolower(str_replace(' ', '_', $title));
        $this->createdAt = time();
        $this->updatedAt = time();
        $this->description = $description;
        $this->mainPicture = $mainPicture;
        $this->author = $author;

        $this->pictures = new ArrayCollection($pictures);
        $this->videos = new ArrayCollection($videos);
        $this->comments = new ArrayCollection();
        $this->groups = new ArrayCollection();

        foreach ($videos as $video) {
            $video->setTrick($this);
        }

        if ($groups !== null) {
            foreach ($groups->getIterator() as $group) {
                $group->addTrick($this);
                $this->groups->add($group);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function update(
        string $title,
        string $description,
        PictureInterface $mainPicture,
        array $pictures = null,
        array $videos = null,
        \ArrayAccess $groups = null
    ): void {
        $this->title = $title;
        $this->slug = strtolower(str_replace(' ', '_', $title));
        $this->updatedAt = time();
        $this->description = $description;
        $this->mainPicture = $mainPicture;
        $this->updatePicture($pictures);
        $this->updateVideo($videos);
        $this->updateGroup($groups);
    }

    /**
     * @inheritdoc
     */
    public function addComment(CommentInterface $comment): void
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
        }
    }

    /**
     * @param array $pictures
     */
    private function updatePicture(array $pictures)
    {
        $this->pictures->clear();
        foreach ($pictures as $picture) {
            $this->pictures->add($picture);
        }
    }

    /**
     * @param array $videos
     */
    private function updateVideo(array $videos)
    {
        foreach ($this->videos->getIterator() as $key => $video) {
            if (!array_key_exists($key, $videos)) {
                $video->unsetTrick($this);
                $this->videos->remove($key);
            }
        }
        foreach ($videos as $key => $video) {
            $this->videos->set($key, $video);
            $video->setTrick($this);
        }
    }

    /**
     * @param \ArrayAccess $groups
     */
    private function updateGroup(\ArrayAccess $groups)
    {
        foreach ($this->groups->getIterator() as $key => $group) {
            if (!isset($groups[$key])) {
                $group->removeTrick($this);
                $this->groups->removeElement($group);
            }
        }
        foreach ($groups as $key => $group) {
            if ($this->groups->containsKey($key)) {
                $group->addTrick($this);
                $this->groups->set($key, $group);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @inheritdoc
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @inheritdoc
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @inheritdoc
     */
    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    /**
     * @inheritdoc
     */
    public function getUpdatedAt(): int
    {
        return $this->updatedAt;
    }

    /**
     * @inheritdoc
     */
    public function getAuthor(): UserInterface
    {
        return $this->author;
    }

    /**
     * @inheritdoc
     */
    public function getMainPicture(): PictureInterface
    {
        return $this->mainPicture;
    }

    /**
     * @inheritdoc
     */
    public function getComments(): \ArrayAccess
    {
        return $this->comments;
    }

    /**
     * @inheritdoc
     */
    public function getPictures(): \ArrayAccess
    {
        return $this->pictures;
    }

    /**
     * @inheritdoc
     */
    public function getVideos(): \ArrayAccess
    {
        return $this->videos;
    }

    /**
     * @inheritdoc
     */
    public function getGroups(): \ArrayAccess
    {
        return $this->groups;
    }
}
