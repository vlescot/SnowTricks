<?php
declare(strict_types = 1);

namespace App\Domain\DTO;

/**
 * Class PictureDTO.
 */
class PictureDTO
{
    /**
     * @var \SplFileInfo
     */
    public $file;

    /**
     * PictureDTO constructor.
     * @param $file
     */
    public function __construct(\SplFileInfo $file = null)
    {
        $this->file = $file;
    }
}
