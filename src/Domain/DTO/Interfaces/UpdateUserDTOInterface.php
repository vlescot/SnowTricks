<?php
declare(strict_types=1);

namespace App\Domain\DTO\Interfaces;

interface UpdateUserDTOInterface
{
    /**
     * UpdateUserDTOInterface constructor.
     *
     * @param string|null $username
     * @param string|null $password
     * @param string|null $email
     * @param PictureDTOInterface|null $picture
     */
    public function __construct(string $username = null, string $password = null, string $email = null, PictureDTOInterface $picture = null);
}
