<?php
declare(strict_types=1);

namespace App\Domain\Entity\Interfaces;

use Ramsey\Uuid\UuidInterface;

interface GroupInterface
{
    /**
     * @param TrickInterface $trick
     */
    public function addTrick(TrickInterface $trick): void;

    /**
     * @param TrickInterface $trick
     */
    public function removeTrick(TrickInterface $trick): void;

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return \ArrayAccess
     */
    public function getTricks(): \ArrayAccess;
}
