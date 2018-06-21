<?php

namespace App\Domain\DTO;

use App\Domain\Entity\Trick;

class VideoDTO
{
    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $platform;

    /**
     * @var Trick
     */
    public $trick;

    /**
     * VideoDTO constructor.
     * @param string $url
     * @param string $platform
     */
    public function __construct(
        string $url,
        string $platform,
        Trick $trick
    ) {
        $this->url = $url;
        $this->platform = $platform;
        $this->trick = $trick;
    }
}
