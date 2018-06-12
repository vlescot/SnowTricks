<?php

namespace App\Domain\Entity;

use App\Domain\DTO\UserDTO;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class User
 * @package App\Domain\Entity
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
    private $validated;

    /**
     * @var boolean
     */
    private $enabled;

    /**
     * @var Picture
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
     * User constructor.
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4();

        $this->comments = new ArrayCollection();
        $this->tricks = new ArrayCollection();

        $this->createdAt = new \DateTime('Europe/Paris');
        $this->validated = false;
        $this->enabled = false;
    }

    /**
     * @param UserDTO $userDTO
     */
    public function registration(UserDTO $userDTO) {
        $this->username = $userDTO->getUsername();
        $this->password = $userDTO->getPassword();
        $this->email = $userDTO->getEmail();
        $this->validated = $userDTO->isValidated();
        $this->token = $userDTO->getToken();
        $this->roles = $userDTO->getRoles();
        $this->enabled = $userDTO->isEnabled();
        $this->picture = $userDTO->getPicture();

        $tricks = $userDTO->getTricks();
        foreach ($tricks->getIterator() as $trick){
            $this->addTrick($trick);
        }

        $comments = $userDTO->getComments();
        foreach ($comments->getIterator() as $comment){
            $this->addComment($comment);
        }
    }

    /**
     * @param Trick $trick
     * @return $this
     */
    private function addTrick(Trick $trick)
    {
        if (!$this->tricks->contains($trick)) {
            $this->tricks[] = $trick;
        }

        return $this;
    }

    /**
     * @param Trick $trick
     * @return User
     */
    private function removeTrick(Trick $trick): self
    {
        if ($this->tricks->contains($trick)) {
            $this->tricks->removeElement($trick);
        }

        return $this;
    }

    /**
     * @param Comment $comment
     * @return $this
     */
    private function addComment(Comment $comment)
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
        }

        return $this;
    }

    /**
     * @param Comment $comment
     * @return User
     */
    private function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
        }

        return $this;
    }
}
