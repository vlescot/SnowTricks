<?php
declare(strict_types = 1);

namespace App\Domain\Entity;

use App\Domain\Entity\Interfaces\PictureInterface;
use App\Domain\Entity\Interfaces\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface as UserInterfaceFrameWork;

class User implements UserInterfaceFrameWork, UserInterface, EquatableInterface
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $email;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var string
     */
    private $token;

    /**
     * @var array
     */
    private $roles = [];

    /**
     * @var Picture
     */
    private $picture;

    /**
     * @var \ArrayAccess
     */
    private $comments;

    /**
     * @var \ArrayAccess
     */
    private $tricks;


    /**
     * User constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->comments = new ArrayCollection();
        $this->tricks = new ArrayCollection();
        $this->createdAt = time();
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     * @param PictureInterface|null $picture
     */
    public function registration(
        string $username,
        string $email,
        string $password,
        PictureInterface $picture = null
    ) {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->picture = $picture;
        $this->token = hash('sha512', uniqid($username, true));
    }

    /**
     * @param string $email
     * @param string $password
     * @param PictureInterface|null $picture
     */
    public function update(
        string $email,
        string $password,
        PictureInterface $picture = null
    ) {
        $this->email = $email;
        $this->password = $password;
        $this->picture = $picture;
    }

    /**
     * @param bool $isConfirmed
     */
    public function setConfirmation(bool $isConfirmed)
    {
        if ($isConfirmed) {
            $this->roles = ['ROLE_USER'];
        } else {
            $this->roles = [];
        }
    }

    /**
     * @param string $password
     */
    public function changePassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return null|string
     */
    public function getPassword(): ? string
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @return Picture
     * @throws \Exception
     */
    public function getPicture(): PictureInterface
    {
        if (null === $this->picture) {
            return new Picture(
                '/image/user/',
                'user_default.png',
                'default-member-picture'
            );
        }
        return $this->picture;
    }

    /**
     * @return null|string
     */
    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        $this->password = null;
        $this->token = null;
    }

    public function isEqualTo(UserInterfaceFrameWork $user)
    {
        return $this->username === $user->getUsername();
    }
}
