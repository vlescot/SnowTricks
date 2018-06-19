<?php

namespace App\Domain\Entity;

use App\Domain\DTO\PictureDTO;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class Picture.
 */
class Picture
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $fileName;

    /**
     * @var string
     */
    private $alt;

    /**
     * @var Trick
     */
    private $trick;

    /**
     * @var User
     */
    private $user;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function add(PictureDTO $pictureDTO)
    {
        $this->path = $pictureDTO->getPath();
        $this->fileName = $pictureDTO->getFileName();
        $this->alt = $pictureDTO->getAlt();
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @return string
     */
    public function getAlt(): string
    {
        return $this->alt;
    }

    /**
     * @return Trick
     */
    public function getTrick(): Trick
    {
        return $this->trick;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
