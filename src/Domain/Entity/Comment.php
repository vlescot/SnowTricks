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
     * @var string
     */
    private $createdAt;

    /**
     * @var boolean
     */
    private $validated = false;

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
        $this->content = $commentDTO->content;
        $this->validated = $commentDTO->validated;
        $this->author = $commentDTO->author;
        $this->trick = $commentDTO->trick;
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
     * @return string
     */
    public function getCreatedAt(): string
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
