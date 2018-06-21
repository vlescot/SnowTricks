<?php

namespace App\Domain\DTO;

use App\Domain\Entity\Picture;

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
     * @var string
     */
    public $token;

    /**
     * @var string
     */
    public $roles;

    /**
     * @var boolean
     */
    public $validated;

    /**
     * @var boolean
     */
    public $enabled;

    /**
     * @var Picture
     */
    public $picture;

    /**
     * @var \ArrayAccess
     */
    public $comments;

    /**
     * @var \ArrayAccess
     */
    public $tricks;

    /**
     * UserDTO constructor.
     * @param string $username
     * @param string $password
     * @param string $email
     * @param string $token
     * @param string $roles
     * @param bool $validated
     * @param bool $enabled
     * @param Picture $picture
     * @param \ArrayAccess $comments
     * @param \ArrayAccess $tricks
     */
    public function __construct(
        string $username,
        string $password,
        string $email,
        string $token,
        string $roles,
        bool $validated,
        bool $enabled,
        Picture $picture,
        \ArrayAccess $comments = null,
        \ArrayAccess $tricks = null
    ) {
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->token = $token;
        $this->roles = $roles;
        $this->validated = $validated;
        $this->enabled = $enabled;
        $this->picture = $picture;
        $this->comments = $comments;
        $this->tricks = $tricks;
    }
}
