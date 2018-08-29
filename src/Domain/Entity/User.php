<?php
declare(strict_types = 1);

namespace App\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User.
 */
class User implements UserInterface
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
     * @param Picture|null $picture
     */
    public function registration(
        string $username,
        string $email,
        string $password,
        Picture $picture = null
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
     * @param Picture|null $picture
     */
    public function update(
        string $email,
        string $password,
        Picture $picture = null
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
     * @param Trick $trick
     */
    public function addTrick(Trick $trick)
    {
        if (!$this->tricks->contains($trick)) {
            $this->tricks->add($trick);
        }
    }

    /**
     * @param Comment $comment
     */
    public function addComment(Comment $comment)
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
        }
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
     * @return string
     */
    public function getPassword(): string
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
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
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
        return ['ROLE_USER'];
    }

    /**
     * @return Picture
     * @throws \Exception
     */
    public function getPicture(): Picture
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
     * @return \ArrayAccess
     */
    public function getComments(): \ArrayAccess
    {
        return $this->comments;
    }

    /**
     * @return \ArrayAccess
     */
    public function getTricks(): \ArrayAccess
    {
        return $this->tricks;
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
//        $this->password = null;
//        $this->username = null;
        $this->token = null;
    }
}
