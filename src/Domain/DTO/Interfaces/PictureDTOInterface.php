<?php
declare(strict_types=1);

namespace App\Domain\DTO\Interfaces;

interface PictureDTOInterface
{
    /**
     * PictureDTOInterface constructor.
     *
     * @param \SplFileInfo|null $file
     */
    public function __construct(\SplFileInfo $file = null);
}
