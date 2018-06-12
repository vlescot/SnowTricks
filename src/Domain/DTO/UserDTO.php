<?php

namespace App\Domain\DTO;



use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\UuidInterface;

/**
 * Class UserDTO
 * @package App\Domain\DTO
 */
class UserDTO
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $email;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $roles;

    /**
     * @var boolean
     */
    private $validated;

    /**
     * @var boolean
     */
    private $enabled;

    /**
     * @var PictureDTO
     */
    private $picture;

    /**
     * @var ArrayCollection
     */
    private $comments;

    /**
     * @var ArrayCollection
     */
    private $tricks;


    /**
     * UserDTO constructor.
     */
    public function __construct()
    {
        $this->tricks = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @param UuidInterface $id
     */
    public function setId(UuidInterface $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getRoles(): string
    {
        return $this->roles;
    }

    /**
     * @param string $roles
     */
    public function setRoles(string $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @return bool
     */
    public function isValidated(): bool
    {
        return $this->validated;
    }

    /**
     * @param bool $validated
     */
    public function setValidated(bool $validated): void
    {
        $this->validated = $validated;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * @return PictureDTO
     */
    public function getPicture(): PictureDTO
    {
        return $this->picture;
    }
    /**
     * @param PictureDTO $picture
     */
    public function setPicture(PictureDTO $picture): void
    {
        $this->picture = $picture;
    }

    /**
     * @param TrickDTO $trick
     * @return UserDTO
     */
    public function addTrick(TrickDTO $trick): self
    {
        if (!$this->tricks->contains($trick)) {
            $this->tricks[] = $trick;
            $trick->setAuthor($this);
        }

        return $this;
    }

    /**
     * @param TrickDTO $trick
     * @return UserDTO
     */
    public function removeTrick(TrickDTO $trick): self
    {
        if ($this->tricks->contains($trick)) {
            $this->tricks->removeElement($trick);

            if ($trick->getAuthor() === $this){
                $trick->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getTricks(): ArrayCollection
    {
        return $this->tricks;
    }


    /**
     * @param CommentDTO $comment
     * @return UserDTO
     */
    public function addComment(CommentDTO $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAuthor($this);
        }

        return $this;
    }

    /**
     * @param CommentDTO $comment
     * @return UserDTO
     */
    public function removeComment(CommentDTO $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);

            if ($comment->getAuthor() === $this){
                $comment->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getComments(): ArrayCollection
    {
        return
            $this->comments;
    }
}