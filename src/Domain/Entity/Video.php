<?php

namespace App\Domain\Entity;
use App\Domain\DTO\VideoDTO;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;


/**
 * Class Video
 * @package App\Domain\Entity
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

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    public function add(VideoDTO $videoDTO)
    {
        $this->url = $videoDTO->getUrl();
        $this->platform = $videoDTO->getPlatform();
    }
}