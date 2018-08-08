<?php
declare(strict_types=1);

namespace App\Domain\DTO;

class ChangePasswordDTO
{
    /**
     * @var string
     */
    public $password;
    /**
     * @var string
     */
    public $token;

    /**
     * ChangePasswordDTO constructor.
     *
     * @param string $token
     * @param string $password
     */
    public function __construct(string $token, string $password = null)
    {
        $this->token = $token;
        $this->password = $password;
    }
}