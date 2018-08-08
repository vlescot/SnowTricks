<?php
declare(strict_types = 1);

namespace App\Domain\Entity;

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
     * @param User $author
     * @param Trick $trick
     * @throws \Exception
     */
    public function __construct(
        string $content,
        User $author,
        Trick $trick
    ) {
        $this->id = Uuid::uuid4();
        $this->createdAt = time();
        $this->content = $content;
        $this->setAuthor($author);
        $this->setTrick($trick);
    }

    /**
     * @param Trick $trick
     */
    private function setTrick(Trick $trick)
    {
        $this->trick = $trick;
    }

    /**
     * @param User $author
     */
    private function setAuthor(User $author)
    {
        $this->author = $author;
        $author->addComment($this);
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
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return Trick
     */
    public function getTrick(): Trick
    {
        return $this->trick;
    }
}
