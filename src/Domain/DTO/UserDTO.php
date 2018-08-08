<?php
declare(strict_types = 1);

namespace App\Domain\DTO;

/**
 * Class UserDTO.
 */
class UserDTO
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
     * @var PictureDTO
     */
    public $picture;

    /**
     * UserDTO constructor.
     *
     * @param string $username
     * @param string $email
     * @param string $password
     * @param PictureDTO|null $picture
     */
    public function __construct(
        string $username,
        string $email,
        string $password,
        PictureDTO $picture = null
    ) {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->picture = $picture;
    }
}
