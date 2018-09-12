<?php
declare(strict_types = 1);

namespace App\Domain\Entity;

use App\Domain\Entity\Interfaces\CommentInterface;
use App\Domain\Entity\Interfaces\TrickInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Comment.
 */
class Comment implements CommentInterface
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
     * @var string
     */
    private $createdAt;
    /**
     * @var User
     */
    private $author;
    /**
     * @var Trick
     */
    private $trick;

    /**
     * Comment constructor.
     *
     * @param string $content
     * @param UserInterface $author
     * @param TrickInterface $trick
     * @throws \Exception
     */
    public function __construct(
        string $content,
        UserInterface $author,
        TrickInterface $trick
    ) {
        $this->id = Uuid::uuid4();
        $this->createdAt = time();
        $this->content = $content;
        $this->author = $author;
        $this->trick = $trick;
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
     * @return UserInterface
     */
    public function getAuthor(): UserInterface
    {
        return $this->author;
    }

    /**
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    /**
     * @return TrickInterface
     */
    public function getTrick(): TrickInterface
    {
        return $this->trick;
    }
}
