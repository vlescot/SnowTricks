<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\Domain\Builder;

use App\Domain\Builder\Interfaces\PictureBuilderInterface;
use App\Domain\Builder\Interfaces\UpdateUserBuilderInterface;
use App\Domain\Builder\UpdateUserBuilder;
use App\Domain\DTO\Interfaces\UpdateUserDTOInterface;
use App\Domain\DTO\PictureDTO;
use App\Domain\DTO\UpdateUserDTO;
use App\Domain\Entity\Interfaces\PictureInterface;
use App\Domain\Entity\User;
use App\Service\Image\Interfaces\ImageRemoverInterface;
use App\Service\Image\Interfaces\ImageUploadWarmerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class UpdateUserBuilderTest extends KernelTestCase
{
    /**
     * @var ImageUploadWarmerInterface
     */
    private $imageUploadWarmer;

    /**
     * @var PictureBuilderInterface
     */
    private $pictureBuilder;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var ImageRemoverInterface
     */
    private $imageRemover;



    public function setUp()
    {
        $this->imageUploadWarmer = $this->createMock(ImageUploadWarmerInterface::class);
        $this->pictureBuilder = $this->createMock(PictureBuilderInterface::class);
        $this->pictureBuilder->method('create')->willReturn(
            $this->createMock(PictureInterface::class)
        );
        $this->passwordEncoder = $this->createMock(UserPasswordEncoderInterface::class);
        $this->passwordEncoder->method('encodePassword')->willReturn(md5('username'));
        $this->imageRemover = $this->createMock(ImageRemoverInterface::class);
    }


    public function constructInstance()
    {
        return new UpdateUserBuilder(
            $this->imageUploadWarmer,
            $this->pictureBuilder,
            $this->passwordEncoder,
            $this->imageRemover
        );
    }


    public function testConstruct()
    {
        $updateUserBuilder = $this->constructInstance();

        static::assertInstanceOf(UpdateUserBuilderInterface::class, $updateUserBuilder);
    }


    /**
     * @param UserInterface $user
     * @param UpdateUserDTOInterface $userDTO
     *
     * @dataProvider provideData
     */
    public function testCreateUser(UserInterface $user, UpdateUserDTOInterface $userDTO)
    {
        $updateUserBuilder = $this->constructInstance();

        $updatedUser = $updateUserBuilder->create($user, $userDTO);

        static::assertInstanceOf(UserInterface::class, $updatedUser);
        static::assertSame($userDTO->email, $updatedUser->getEmail());
        static::assertSame($user->getUsername(), $updatedUser->getUsername());
    }


    /**
     * @return \Generator
     *
     * @throws \Exception
     */
    public function provideData()
    {
        static::bootKernel();
        $imagePath = $this::$kernel->getRootDir() . '/../public/image/tests/r1.png';
        $pictureDTO = new PictureDTO(new File($imagePath));

        $updateUserDTO = new UpdateUserDTO(
            null,
            'username@mail.com',
            'azerty00',
            $pictureDTO
        );

        $user = new User();
        $user->registration(
            'username',
            'user@mail.com',
            'password'
        );

        yield [$user, $updateUserDTO];
    }
}
