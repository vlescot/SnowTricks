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
     * {@inheritdoc}
     */
    public function __construct(string $iFrame)
    {
        $this->iFrame = $iFrame;
    }
}
