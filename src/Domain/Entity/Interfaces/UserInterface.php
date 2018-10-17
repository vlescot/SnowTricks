<?php
declare(strict_types=1);

namespace App\Domain\Entity\Interfaces;

use Ramsey\Uuid\UuidInterface;

interface UserInterface
{
    /**
     * UserInterface constructor.
     */
    public function __construct();

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     * @param PictureInterface|null $picture
     *
     * @return mixed
     */
    public function registration(string $username, string $email, string $password, PictureInterface $picture = null);

    /**
     * @param string $email
     * @param string $password
     * @param PictureInterface|null $picture
     *
     * @return mixed
     */
    public function update(string $email, string $password, PictureInterface $picture = null);

    /**
     * @param bool $isConfirmed
     *
     * @return mixed
     */
    public function setConfirmation(bool $isConfirmed);

    /**
     * @param string $password
     *
     * @return mixed
     */
    public function changePassword(string $password);

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface;

    /**
     * @return string
     */
    public function getEmail(): string;

    /**
     * @return int
     */
    public function getCreatedAt(): int;

    /**
     * @return string
     */
    public function getToken(): string;

    /**
     * @return PictureInterface
     */
    public function getPicture(): PictureInterface;
}
