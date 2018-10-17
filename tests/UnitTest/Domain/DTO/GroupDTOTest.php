<?php
declare(strict_types=1);

namespace Tests\UnitTest\Domain\DTO;

use App\Domain\DTO\GroupDTO;
use PHPUnit\Framework\TestCase;

final class GroupDTOTest extends TestCase
{
    /**
     * @param string $name
     *
     * @dataProvider provideData
     */
    public function testImplements(string $name)
    {
        $dto = new GroupDTO($name);

        static::assertSame($name, $dto->name);
    }

    /**
     * @return \Generator
     */
    public function provideData()
    {
        yield ['Group'];
        yield ['Another Group'];
    }
}
