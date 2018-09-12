<?php
declare(strict_types=1);

namespace App\Domain\DTO\Interfaces;

interface CommentDTOInterface
{
    /**
     * CommentDTOInterface constructor.
     *
     * @param string $content
     */
    public function __construct(string $content);
}
