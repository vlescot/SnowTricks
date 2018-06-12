<?php

namespace App\Domain\Entity;
use App\Domain\DTO\PictureDTO;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;


/**
 * Class Picture
 * @package App\Domain\Entity
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

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function add (PictureDTO $pictureDTO) {
        $this->path = $pictureDTO->getPath();
        $this->fileName = $pictureDTO->getFileName();
        $this->alt = $pictureDTO->getAlt();
    }
}