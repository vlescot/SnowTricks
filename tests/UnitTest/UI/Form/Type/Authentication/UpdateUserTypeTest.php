<?php
declare(strict_types=1);

namespace Tests\UnitTest\UI\Form\Type\Authentication;

use App\Domain\DTO\PictureDTO;
use App\Domain\DTO\UpdateUserDTO;
use App\UI\Form\Subscriber\UpdateUserSubscriber;
use App\UI\Form\Type\Authentication\UpdateUserType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class UpdateUserTypeTest extends TypeTestCase
{
    /**
     * @var UpdateUserSubscriber|null
     */
    private $subscriber = null;

    public function setUp()
    {
        $this->subscriber = new UpdateUserSubscriber();

        parent::setUp();
    }

    protected function getExtensions()
    {
        $type = new UpdateUserType($this->subscriber);

        return [
            new PreloadedExtension([$type], [])
        ];
    }

    public function testImplements()
    {
        $type = new UpdateUserType($this->subscriber);

        static::assertInstanceOf(AbstractType::class, $type);
        static::assertInstanceOf(UpdateUserType::class, $type);
    }

    /**
     * @param string $email
     * @param string $password
     *
     * @dataProvider provideCredentials
     */
    public function testSubmitValidData(string $email, string $password, $file)
    {
        $oldUpdateUserDTO = new UpdateUserDTO(
            'username',
            null,
            'username@mail.com',
            new PictureDTO($this->createMock(File::class))
        );

        $type = $this->factory->create(UpdateUserType::class, $oldUpdateUserDTO);

        $formData = [
            'email' => $email,
            'password' => [
                'first' => $password,
                'second' => $password
            ],
            'picture' => [
                'file' => $file
            ]
        ];

        $type->submit($formData);

        static::assertTrue($type->isValid());
        static::assertTrue($type->isSubmitted());
        static::assertInstanceOf(UpdateUserDTO::class, $type->getData());
        static::assertSame($email, $type->getData()->email);
        static::assertSame($password, $type->getData()->password);
    }

    /**
     * @return \Generator
     */
    public function provideCredentials()
    {
        yield ['toto@mail.com', 'azerty78', $this->createMock(UploadedFile::class)];
        yield ['toti@mail.com', 'uhgruhgreohpugr9', $this->createMock(UploadedFile::class)];
        yield ['tata@mail.com', 're5hhre', $this->createMock(UploadedFile::class)];
    }
}
