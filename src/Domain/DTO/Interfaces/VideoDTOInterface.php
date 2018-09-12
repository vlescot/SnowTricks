<?php
declare(strict_types=1);

namespace App\Domain\DTO\Interfaces;

interface VideoDTOInterface
{
    /**
     * VideoDTOInterface constructor.
     *
     * @param string $iFrame
     */
    public function __construct(string $iFrame);
}
