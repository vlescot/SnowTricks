<?php
declare(strict_types = 1);

namespace App\Domain\DTO;

use App\Domain\DTO\Interfaces\PictureDTOInterface;
use App\Domain\DTO\Interfaces\UserDTOInterface;

/**
 * Class UserDTO.
 */
final class UserDTO implements UserDTOInterface
{
    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $email;

    /**
     * @var PictureDTOInterface|null
     */
    public $picture;

    /**
     * UserDTO constructor.
     *
     * @param string $username
     * @param string $email
     * @param string $password
     * @param PictureDTOInterface|null $picture
     */
    public function __construct(
        string $username,
        string $email,
        string $password,
        PictureDTOInterface $picture = null
    ) {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->picture = $picture;
    }
}
