<?php
declare(strict_types=1);

namespace App\Domain\DTO;

use App\Domain\DTO\Interfaces\ChangePasswordDTOInterface;

final class ChangePasswordDTO implements ChangePasswordDTOInterface
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
