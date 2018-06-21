<?php

namespace App\Domain\Entity;

use App\Domain\DTO\VideoDTO;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class Video.
 */
class Video
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
     * @var Trick
     */
    private $trick;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function add(VideoDTO $videoDTO)
    {
        $this->url = $videoDTO->url;
        $this->platform = $videoDTO->platform;
        $this->trick = $videoDTO->trick;
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
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getPlatform(): string
    {
        return $this->platform;
    }

    /**
     * @return Trick
     */
    public function getTrick(): Trick
    {
        return $this->trick;
    }
}
