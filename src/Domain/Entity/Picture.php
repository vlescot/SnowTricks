<?php
declare(strict_types = 1);

namespace App\Domain\Entity;

use App\Domain\Entity\Interfaces\PictureInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Picture implements PictureInterface
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
    private $filename;

    /**
     * @var string
     */
    private $alt;

    /**
     * @inheritdoc
     */
    public function __construct(
        string $path,
        string $filename,
        string $alt
    ) {
        $this->id = Uuid::uuid4();

        $this->path = $path;
        $this->filename = $filename;
        $this->alt = $alt;
    }

    /**
     * @inheritdoc
     */
    public function update(string $path): void
    {
        $this->path = $path;
    }

    /**
     * @inheritdoc
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @inheritdoc
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @inheritdoc
     */
    public function getAlt(): string
    {
        return $this->alt;
    }

    /**
     * @inheritdoc
     */
    public function getWebPath(): string
    {
        return $this->path . '/' . $this->filename;
    }
}
