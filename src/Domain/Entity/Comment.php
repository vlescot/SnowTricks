<?php

namespace App\Domain\Entity;

use App\Domain\DTO\CommentDTO;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;


/**
 * Class Comment
 * @package App\Domain\Entity
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

    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->createdAt = new \DateTime('Europe/Paris');
    }

    public function add(CommentDTO $commentDTO) {
        $this->author = $commentDTO->getAuthor();
        $this->content = $commentDTO->getContent();
        $this->validated = $commentDTO->isValidated();
    }
}
