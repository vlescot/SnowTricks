<?php
declare(strict_types=1);

namespace App\Tests\Domain\DTO;

use PHPUnit\Framework\TestCase;
use App\Domain\DTO\GroupDTO;

class GroupDTOTest extends TestCase
{
    /**
     * @var GroupDTO
     */
    private $dto;

    public function setUp()
    {
        $this->dto = new GroupDTO('New Group');
    }

    public function testGroupDTOAttributeMustBeAString()
    {
        static::assertInternalType('string', $this->dto->name);
    }
}
