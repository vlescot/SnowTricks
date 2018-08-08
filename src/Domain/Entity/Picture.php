<?php
declare(strict_types = 1);

namespace App\Domain\Entity;

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
    private $filename;

    /**
     * @var string
     */
    private $alt;

    /**
     * Picture constructor.
     *
     * @param string $path
     * @param string $filename
     * @param string $alt
     * @throws \Exception
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
     * @param string $path
     * @param string $alt
     */
    public function update(
        string $path,
        string $alt
    ) {
        $this->path = $path;
        $this->alt = $alt;
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
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getAlt(): string
    {
        return $this->alt;
    }

    /**
     * @return string
     */
    public function getWebPath(): string
    {
        return $this->path . '/' . $this->filename;
    }
}
