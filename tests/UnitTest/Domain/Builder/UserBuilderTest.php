<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\Domain\Builder;

use App\Domain\Builder\Interfaces\PictureBuilderInterface;
use App\Domain\Builder\Interfaces\UserBuilderInterface;
use App\Domain\Builder\UserBuilder;
use App\Domain\DTO\Interfaces\UserDTOInterface;
use App\Domain\DTO\PictureDTO;
use App\Domain\DTO\UserDTO;
use App\Domain\Entity\Interfaces\PictureInterface;
use App\Service\Image\Interfaces\ImageUploadWarmerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserBuilderTest extends KernelTestCase
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var ImageUploadWarmerInterface
     */
    private $imageUploadWarmer;

    /**
     * @var PictureBuilderInterface
     */
    private $pictureBuilder;


    public function setUp()
    {
        $this->passwordEncoder = $this->createMock(UserPasswordEncoderInterface::class);
        $this->passwordEncoder->method('encodePassword')->willReturn(md5('username'));
        $this->imageUploadWarmer = $this->createMock(ImageUploadWarmerInterface::class);
        $this->pictureBuilder = $this->createMock(PictureBuilderInterface::class);
        $this->pictureBuilder->method('create')->willReturn(
            $this->createMock(PictureInterface::class)
        );
    }


    public function constructInstance()
    {
        return new UserBuilder(
            $this->passwordEncoder,
            $this->imageUploadWarmer,
            $this->pictureBuilder
        );
    }


    public function testConstruct()
    {
        $userBuilder = $this->constructInstance();

        static::assertInstanceOf(UserBuilderInterface::class, $userBuilder);
    }


    /**
     * @param UserDTOInterface $userDTO
     *
     * @dataProvider provideData
     */
    public function testCreateUser(UserDTOInterface $userDTO)
    {
        $userBuilder = $this->constructInstance();

        $user = $userBuilder->create($userDTO);

        static::assertInstanceOf(UserInterface::class, $user);
        static::assertSame($userDTO->username, $user->getUsername());
        static::assertSame($userDTO->email, $user->getEmail());
    }


    /**
     * @return \Generator
     */
    public function provideData()
    {
        static::bootKernel();
        $imagePath = $this::$kernel->getRootDir() . '/../public/image/tests/r1.png';
        $pictureDTO = new PictureDTO(new File($imagePath));

        yield [
            new UserDTO(
                'username',
                'username@mail.com',
                'azerty00',
                $pictureDTO
            )
        ];
    }
}
