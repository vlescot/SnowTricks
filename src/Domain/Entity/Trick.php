<?php

namespace App\Domain\Entity;

use App\Domain\DTO\TrickDTO;
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
     * @var \DateTime;
     */
    private $createdAt;

    /**
     * @var \DateTime
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
     * @var ArrayCollection
     */
    private $comments;

    /**
     * @var ArrayCollection
     */
    private $pictures;

    /**
     * @var ArrayCollection
     */
    private $videos;

    /**
     * @var ArrayCollection
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
        $this->title = $trickDTO->getTitle();
        $this->slug = $trickDTO->getSlug();
        $this->description = $trickDTO->getDescription();
        $this->validated = $trickDTO->isValidated();
//        $this->author = $trickDTO->getAuthor();

        $pictures = $trickDTO->getPictures();
        foreach ($pictures->getIterator() as $picture) {
            $this->addPicture($picture);
        }

//        if ($videos = $trickDTO->getVideos()){
//            foreach ($videos->getIterator() as $video){
//                $this->addVideo($video);
//            }
//        }

//        if ($comments = $trickDTO->getComments()){
//            foreach ($comments->getIterator() as $comment){
//                $this->addComment($comment);
//            }
//        }

//        if ($groups = $trickDTO->getGroups()){
//            foreach ($groups->getIterator() as $group){
//                $this->addGroup($group);
//            }
//        }
    }

    /**
     * @param Comment $comment
     *
     * @return Trick
     */
    private function addComment(Comment $comment): self
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
    private function removeComment(Comment $comment): self
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
    private function addPicture(Picture $picture): self
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
    private function removePicture(Picture $picture): self
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
    private function addVideo(Video $video): self
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
    private function removeVideo(Video $video): self
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
    private function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
        }

        return $this;
    }

    /**
     * @param Group $group
     *
     * @return Trick
     */
    private function removeGroup(Group $group): self
    {
        if ($this->groups->contains($group)) {
            $this->groups->removeElement($group);
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
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
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
     * @return ArrayCollection
     */
    public function getComments(): ArrayCollection
    {
        return $this->comments;
    }

    /**
     * @return ArrayCollection
     */
    public function getPictures(): ArrayCollection
    {
        return $this->pictures;
    }

    /**
     * @return ArrayCollection
     */
    public function getVideos(): ArrayCollection
    {
        return $this->videos;
    }

    /**
     * @return ArrayCollection
     */
    public function getGroups(): ArrayCollection
    {
        return $this->groups;
    }
}
