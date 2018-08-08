<?php
declare(strict_types=1);

namespace App\Domain\DTO;

class UpdateUserDTO
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
     * UpdateUserDTO constructor.
     *
     * @param string $username
     * @param string $email
     * @param PictureDTO|null $picture
     */
    public function __construct(
        string $username,
        string $email,
        PictureDTO $picture = null
    ) {
        $this->username = $username;
        $this->email = $email;
        $this->picture = $picture;
    }

    public function update(
        string $password = null,
        string $email = null,
        PictureDTO $picture = null
    ) {
        $this->password = $password;
        $this->email = $email;
        $this->picture = $picture;
    }
}
