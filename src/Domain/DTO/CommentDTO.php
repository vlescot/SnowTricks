<?php
declare(strict_types = 1);

namespace App\Domain\DTO;

use App\Domain\Entity\Trick;
use App\Domain\Entity\User;

class CommentDTO
{
    /**
     * @var string
     */
    public $content;

    /**
     * CommentDTO constructor.
     * @param string $content
     */
    public function __construct(
        string $content
    ) {
        $this->content = $content;
    }
}
