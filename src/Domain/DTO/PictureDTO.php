<?php
declare(strict_types = 1);

namespace App\Domain\DTO;

use App\Domain\DTO\Interfaces\PictureDTOInterface;

/**
 * Class PictureDTO.
 */
final class PictureDTO implements PictureDTOInterface
{
    /**
     * @var \SplFileInfo
     */
    public $file;

    /**
     * {@inheritdoc}
     */
    public function __construct(\SplFileInfo $file = null)
    {
        $this->file = $file;
    }
}
