<?php

namespace App\Domain\Entity;

use App\Domain\DTO\UserDTO;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class User.
 */
class User
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
    private $validated = false;

    /**
     * @var boolean
     */
    private $enabled = true;

    /**
     * @var Picture
     */
    private $picture;

    /**
     * @var \ArrayAccess
     */
    private $comments;

    /**
     * @var \ArrayAccess
     */
    private $tricks;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4();

        $this->comments = new ArrayCollection();
        $this->tricks = new ArrayCollection();

        $this->createdAt = time();
        $this->validated = false;
        $this->enabled = false;
    }

    /**
     * @param UserDTO $userDTO
     */
    public function registration(UserDTO $userDTO)
    {
        $this->username = $userDTO->username;
        $this->password = $userDTO->password;
        $this->email = $userDTO->email;
        $this->validated = $userDTO->validated;
        $this->token = $userDTO->token;
        $this->roles = $userDTO->roles;
        $this->enabled = $userDTO->enabled;
        $this->picture = $userDTO->picture;
    }

    /**
     * @param Trick $trick
     *
     * @return $this
     */
    public function addTrick(Trick $trick): self
    {
        if (!$this->tricks->contains($trick)) {
            $this->tricks[] = $trick;
        }

        return $this;
    }

    /**
     * @param Trick $trick
     *
     * @return User
     */
    public function removeTrick(Trick $trick): self
    {
        if ($this->tricks->contains($trick)) {
            $this->tricks->removeElement($trick);
        }

        return $this;
    }

    /**
     * @param Comment $comment
     *
     * @return $this
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
     * @return User
     */
    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
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
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getRoles(): string
    {
        return $this->roles;
    }

    /**
     * @return bool
     */
    public function isValidated(): bool
    {
        return $this->validated;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @return Picture
     */
    public function getPicture(): Picture
    {
        return $this->picture;
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
    public function getTricks(): \ArrayAccess
    {
        return $this->tricks;
    }
}
