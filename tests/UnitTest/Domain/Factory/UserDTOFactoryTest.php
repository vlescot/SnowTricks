<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\Domain\Factory;

use App\Domain\DTO\Interfaces\PictureDTOInterface;
use App\Domain\DTO\UpdateUserDTO;
use App\Domain\Entity\Interfaces\PictureInterface;
use App\Domain\Entity\User;
use App\Domain\Factory\Interfaces\PictureDTOFactoryInterface;
use App\Domain\Factory\Interfaces\UserDTOFactoryInterface;
use App\Domain\Factory\PictureDTOFactory;
use App\Domain\Factory\UserDTOFactory;
use PHPUnit\Framework\TestCase;

final class UserDTOFactoryTest extends TestCase
{
    /**
     * @var PictureDTOFactory
     */
    private $pictureDTOFactory;

    public function setUp()
    {
        $this->pictureDTOFactory = $this->createMock(PictureDTOFactoryInterface::class);

        $picture = $this->createMock(PictureDTOInterface::class);
        $this->pictureDTOFactory->method('create')->willReturn($picture);
    }

    public function testConstruct()
    {
        $userDTOFactory = new UserDTOFactory(
            $this->pictureDTOFactory
        );

        static::assertInstanceOf(UserDTOFactoryInterface::class, $userDTOFactory);
    }

    public function testReturnGoodValue()
    {
        $picture = $this->createMock(PictureInterface::class);

        $user = new User();
        $user->registration(
            'username',
            'username@mail.com',
            'pa55w0rd',
            $picture
        );

        $userDTOFactory = new UserDTOFactory(
            $this->pictureDTOFactory
        );

        $userDTO = $userDTOFactory->create($user);

        static::assertInstanceOf(UpdateUserDTO::class, $userDTO);
    }
}
