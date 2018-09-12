<?php
declare(strict_types=1);

namespace Tests\UnitTest\Domain\DTO;

use App\Domain\DTO\ChangePasswordDTO;
use PHPUnit\Framework\TestCase;

final class ChangePasswordDTOTest extends TestCase
{
    /**
     * @param string $password
     *
     * @dataProvider provideData
     */
    public function testImplements(string $password)
    {
        $dto = new ChangePasswordDTO($password);

        static::assertSame($password, $dto->password);
    }

    /**
     * @return \Generator
     */
    public function provideData()
    {
        yield ['passW707d'];
        yield ['ch_52word'];
    }
}
