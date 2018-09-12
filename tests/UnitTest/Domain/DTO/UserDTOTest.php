<?php
declare(strict_types=1);

namespace Tests\UnitTest\Domain\DTO;

use App\Domain\DTO\Interfaces\PictureDTOInterface;
use App\Domain\DTO\UserDTO;
use Doctrine\ORM\Mapping as Embedded;
use PHPUnit\Framework\TestCase;

/**
 * @Embedded\Embedded
 */
final class UserDTOTest extends TestCase
{
    /**
     * @param string $username
     * @param string $email
     * @param string $password
     *
     * @dataProvider provideCredentials
     */
    public function testImplements(string $username, string $email, string $password)
    {
        $pictureDTO = $this->createMock(PictureDTOInterface::class);

        $dto = new UserDTO($username, $email, $password, $pictureDTO);

        static::assertSame($username, $dto->username);
        static::assertSame($email, $dto->email);
        static::assertSame($password, $dto->password);
        static::assertSame($pictureDTO, $dto->picture);
    }


    /**
     * @return \Generator
     */
    public function provideCredentials()
    {
        yield ['Toto', 'toto@mail.com', 'azerty78'];
        yield ['Toti', 'toti@mail.com', 'uhgruhgreohpugr9'];
        yield ['Tata', 'tata@mail.com', 're5hhre'];
    }
}
