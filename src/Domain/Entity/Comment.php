<?php

namespace App\Domain\Entity;

use App\Domain\DTO\CommentDTO;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class Comment.
 */
class Comment
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var string
     */
    private $content;

    /**
     * @var User
     */
    private $author;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var boolean
     */
    private $validated;

    /**
     * @var Trick
     */
    private $trick;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->createdAt = time();
    }

    public function add(CommentDTO $commentDTO)
    {
        $this->author = $commentDTO->getAuthor();
        $this->content = $commentDTO->getContent();
        $this->validated = $commentDTO->isValidated();
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
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return bool
     */
    public function isValidated(): bool
    {
        return $this->validated;
    }

    /**
     * @return Trick
     */
    public function getTrick(): Trick
    {
        return $this->trick;
    }
}
