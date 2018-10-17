<?php
declare(strict_types=1);

namespace App\Domain\Entity\Interfaces;

use Ramsey\Uuid\UuidInterface;

interface PictureInterface
{
    /**
     * @param string $path
     */
    public function setPath(string $path): void;

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface;

    /**
     * @return string
     */
    public function getPath(): string;

    /**
     * @return string
     */
    public function getFilename(): string;

    /**
     * @return string
     */
    public function getAlt(): string;

    /**
     * @return string
     */
    public function getWebPath(): string;
}
