<?php
declare(strict_types = 1);

namespace App\Domain\DTO;

class VideoDTO
{
    /**
     * @var string
     */
    public $iFrame;

    /**
     * VideoDTO constructor.
     * @param string $iFrame
     */
    public function __construct(string $iFrame)
    {
        $this->iFrame = $iFrame;
    }
}
