<?php
declare(strict_types=1);

namespace App\Domain\DTO;

use App\Domain\DTO\Interfaces\PictureDTOInterface;
use App\Domain\DTO\Interfaces\UpdateUserDTOInterface;

final class UpdateUserDTO implements UpdateUserDTOInterface
{
    /**
     * @var null|string
     */
    public $username;

    /**
     * @var null|string
     */
    public $password;

    /**
     * @var null|string
     */
    public $email;

    /**
     * @var null|PictureDTOInterface
     */
    public $picture;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        string $username = null,
        string $password = null,
        string $email = null,
        PictureDTOInterface $picture = null
    ) {
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->picture = $picture;
    }
}
