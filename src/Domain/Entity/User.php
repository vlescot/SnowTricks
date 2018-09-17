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
     * @inheritdoc
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->comments = new ArrayCollection();
        $this->tricks = new ArrayCollection();
        $this->createdAt = time();
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
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
     * @inheritdoc
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
     * @inheritdoc
     */
    public function changePassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @inheritdoc
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @inheritdoc
     */
    public function getPassword(): ? string
    {
        return $this->password;
    }

    /**
     * @inheritdoc
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @inheritdoc
     */
    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    /**
     * @inheritdoc
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @inheritdoc
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
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
