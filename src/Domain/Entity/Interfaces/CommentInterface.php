<?php
declare(strict_types=1);

namespace App\Domain\Entity\Interfaces;

use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

interface CommentInterface
{
    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface;

    /**
     * @return string
     */
    public function getContent(): string;

    /**
     * @return UserInterface
     */
    public function getAuthor(): UserInterface;

    /**
     * @return int
     */
    public function getCreatedAt(): int;

    /**
     * @return TrickInterface
     */
    public function getTrick(): TrickInterface;
}
