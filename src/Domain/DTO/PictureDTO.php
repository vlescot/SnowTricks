<?php

namespace App\Domain\DTO;

use App\Domain\Entity\Trick;
use App\Domain\Entity\User;

/**
 * Class PictureDTO.
 */
class PictureDTO
{
    /**
     * @var string
     */
    public $path;

    /**
     * @var string
     */
    public $fileName;

    /**
     * @var string
     */
    public $alt;

    /**
     * @var Trick
     */
    public $trick;

    /**
     * PictureDTO constructor.
     * @param string $path
     * @param string $fileName
     * @param string $alt
     */
    public function __construct(
        string $path,
        string $fileName,
        string $alt,
        Trick $trick = null
    ) {
        $this->path = $path;
        $this->fileName = $fileName;
        $this->alt = $alt;
        $this->trick = $trick;
    }
}
