<?php
declare(strict_types=1);

namespace App\Domain\Repository\Interfaces;

use App\Domain\Entity\Interfaces\CommentInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

interface CommentRepositoryInterface
{
    /**
     * CommentRepositoryInterface constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry);

    /**
     * @param CommentInterface $comment
     */
    public function save(CommentInterface $comment): void;
}
