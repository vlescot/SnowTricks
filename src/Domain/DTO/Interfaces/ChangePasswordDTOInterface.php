<?php
declare(strict_types=1);

namespace App\Domain\DTO\Interfaces;

interface ChangePasswordDTOInterface
{
    /**
     * ChangePasswordDTOInterface constructor.
     *
     * @param string $password
     */
    public function __construct(string $password);
}
