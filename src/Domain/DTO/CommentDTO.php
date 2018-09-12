<?php
declare(strict_types = 1);

namespace App\Domain\DTO;

use App\Domain\DTO\Interfaces\CommentDTOInterface;

final class CommentDTO implements CommentDTOInterface
{
    /**
     * @var string
     */
    public $content;

    /**
     * CommentDTO constructor.
     *
     * @param string $content
     */
    public function __construct(string $content)
    {
        $this->content = $content;
    }
}
