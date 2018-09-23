<?php
declare(strict_types=1);

namespace App\Domain\Entity\Interfaces;

use Ramsey\Uuid\UuidInterface;

interface VideoInterface
{
    /**
     * @param TrickInterface $trick
     *
     * @return mixed
     */
    public function setTrick(TrickInterface $trick);

    /**
     * @return mixed
     */
    public function unsetTrick();

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface;

    /**
     * @return string
     */
    public function getIFrame(): string;

    /**
     * @return TrickInterface
     */
    public function getTrick(): TrickInterface;
}
