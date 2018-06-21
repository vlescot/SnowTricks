<?php

namespace App\Domain\DTO;

use App\Domain\Entity\Trick;
use App\Domain\Entity\User;

/**
 * Class CommentDTO.
 */
class CommentDTO
{
    /**
     * @var string
     */
    public $content;

    /**
     * @var bool
     */
    public $validated = false;

    /**
     * @var User
     */
    public $author;

    /**
     * @var Trick
     */
    public $trick;

    /**
     * CommentDTO constructor.
     * @param string $content
     * @param User $author
     * @param Trick $trick
     */
    public function __construct(
        string $content,
        bool $validated,
        User $author,
        Trick $trick
    ) {
        $this->content = $content;
        $this->validated = $validated;
        $this->author = $author;
        $this->trick = $trick;
    }
}
