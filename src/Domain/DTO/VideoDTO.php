<?php
declare(strict_types = 1);

namespace App\Domain\DTO;

use App\Domain\DTO\Interfaces\VideoDTOInterface;

final class VideoDTO implements VideoDTOInterface
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
