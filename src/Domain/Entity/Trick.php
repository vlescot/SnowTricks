<?php
declare(strict_types = 1);

namespace App\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * Trick constructor.
     *
     * @param string $title
     * @param string $description
     * @param User $author
     * @param Picture $mainPicture
     * @param null $pictures
     * @param array|null $videos
     * @param \ArrayAccess|null $groups
     * @throws \Exception
     */
    public function __construct(
        string $title,
        string $description,
        User $author,
        Picture $mainPicture,
        $pictures = null,
        array $videos = null,
        \ArrayAccess $groups = null
    ) {
        $this->pictures = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->comments = new ArrayCollection();

        $this->id = Uuid::uuid4();
        $this->title = $title;
        $this->slug = strtolower(str_replace(' ', '_', $title));
        $this->createdAt = time();
        $this->updatedAt = time();
        $this->description = $description;
        $this->mainPicture = $mainPicture;
        $this->setAuthor($author);

        foreach ($pictures as $picture) {
            $this->pictures->add($picture);
        }
        foreach ($videos as $video) {
            $this->videos->add($video);
            $video->setTrick($this);
        }
        foreach ($groups->getIterator() as $group) {
            $this->addGroup($group);
        }
    }

    /**
     * @param string $title
     * @param string $description
     * @param Picture $mainPicture
     * @param null $pictures
     * @param array|null $videos
     * @param \ArrayAccess|null $groups
     */
    public function update(
        string $title,
        string $description,
        Picture $mainPicture,
        $pictures = null,
        array $videos = null,
        \ArrayAccess $groups = null
    ) {
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
     * @param $pictures
     */
    private function updatePicture($pictures)
    {
        foreach ($pictures as $key => $picture) {
            if ($this->pictures->containsKey($key)) {
                $this->pictures->set($key, $picture);
            } else {
                $this->pictures->add($picture);
            }
        }
        foreach ($this->pictures->getIterator() as $key => $picture) {
            if (!isset($pictures[$key])) {
                $this->pictures->remove($key);
            }
        }
    }

    /**
     * @param $videos
     */
    private function updateVideo(array $videos)
    {
        foreach ($videos as $key => $video) {
            if ($this->videos->containsKey($key)) {
                $this->videos->set($key, $video);
            } else {
                $this->videos->add($video);
            }
        }
        foreach ($this->videos->getIterator() as $key => $video) {
            if (!isset($videos[$key])) {
                $this->videos->remove($key);
            }
        }
    }

    /**
     * @param $groups
     */
    private function updateGroup($groups)
    {
        foreach ($groups as $key => $group) {
            if ($this->groups->containsKey($key)) {
                $this->groups->set($key, $group);
            } else {
                $this->addGroup($group);
            }
        }
        foreach ($this->groups->getIterator() as $key => $group) {
            if (!isset($groups[$key])) {
                $this->removeGroup($group);
            }
        }
    }

    /**
     * @param User $author
     */
    private function setAuthor(User $author)
    {
        $this->author = $author;
        $author->addTrick($this);
    }

    /**
     * @param Group $group
     */
    private function addGroup(Group $group)
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
            $group->addTrick($this);
        }
    }

    /**
     * @param Group $group
     */
    private function removeGroup(Group $group)
    {
        if ($this->groups->contains($group)) {
            $this->groups->removeElement($group);
            $group->removeTrick($this);
        }
    }

    /**
     * @param Comment $comment
     */
    public function addComment(Comment $comment)
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
        }
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
