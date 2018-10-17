<?php
declare(strict_types=1);

namespace Tests\UnitTest\Domain\DTO;

use App\Domain\DTO\Interfaces\PictureDTOInterface;
use App\Domain\DTO\UpdateUserDTO;
use PHPUnit\Framework\TestCase;

final class UpdateUserDTOTest extends TestCase
{
    public function testReturnGoodValues()
    {
        $pictureDTO = $this->createMock(PictureDTOInterface::class);

        $dto = new UpdateUserDTO('Toto', 'azerty0', 'toto@mail.com', $pictureDTO);

        static::assertSame('Toto', $dto->username);
        static::assertSame('azerty0', $dto->password);
        static::assertSame('toto@mail.com', $dto->email);
        static::assertSame($pictureDTO, $dto->picture);
    }
}
