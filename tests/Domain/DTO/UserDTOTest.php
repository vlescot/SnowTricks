<?php
declare(strict_types=1);

namespace App\Tests\Domain\DTO;

use PHPUnit\Framework\TestCase;
use App\Domain\DTO\UserDTO;

class UserDTOTest extends TestCase
{
    /**
     * @var UserDTO
     */
    private $dto;

    public function setUp()
    {
        $userDTO = new UserDTO();
        $userDTO->registration(
            'Floyd',
            'floyd@gmail.com',
            'azerty'
        );
        $this->dto = $userDTO;
    }

    public function testUserDTOTODO()
    {
    }
}
