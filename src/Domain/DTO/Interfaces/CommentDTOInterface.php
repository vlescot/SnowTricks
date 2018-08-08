<?php

namespace App\Domain\DTO\Interfaces;

/**
 * Interface CommentDTOInterface
 * @package App\Domain\DTO\Interfaces
 */
interface CommentDTOInterface
{
    /**
     * CommentDTOInterface constructor.
     * @param string $content
     */
    public function __construct(
        string $content
    );
}
