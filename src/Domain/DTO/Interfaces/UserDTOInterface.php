<?php
declare(strict_types=1);

namespace App\Domain\DTO\Interfaces;

interface UserDTOInterface
{
    /**
     * UserDTOInterface constructor.
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
    );
}
