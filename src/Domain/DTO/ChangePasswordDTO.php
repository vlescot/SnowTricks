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
     * ChangePasswordDTO constructor.
     *
     * @param string $password
     */
    public function __construct(string $password)
    {
        $this->password = $password;
    }
}
