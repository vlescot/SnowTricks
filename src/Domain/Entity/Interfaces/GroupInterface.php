<?php
declare(strict_types=1);

namespace App\Domain\Entity\Interfaces;

use Ramsey\Uuid\UuidInterface;

interface GroupInterface
{
    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface;

    /**
     * @return string
     */
    public function getName(): string;
}
