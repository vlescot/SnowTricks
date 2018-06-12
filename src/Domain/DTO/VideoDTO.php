<?php

namespace App\Domain\DTO;


use Ramsey\Uuid\UuidInterface;

class VideoDTO
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $platform;

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @param UuidInterface $id
     */
    public function setId(UuidInterface $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getPlatform(): string
    {
        return $this->platform;
    }
    /**
     * @param string $platform
     */
    public function setPlatform(string $platform): void
    {
        $this->platform = $platform;
    }
}