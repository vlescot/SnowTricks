<?php
declare(strict_types=1);

namespace Tests\UnitTest\UI\Form\Type\Authentication;

use App\Domain\DTO\UserDTO;
use App\UI\Form\Type\Authentication\RegistrationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Test\TypeTestCase;

final class RegistrationTypeTest extends TypeTestCase
{
    public function testImplements()
    {
        $type = new RegistrationType();

        static::assertInstanceOf(AbstractType::class, $type);
        static::assertInstanceOf(RegistrationType::class, $type);
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     *
     * @dataProvider provideCredentials
     */
    public function testSubmitValidData(string $username, string $email, string $password)
    {
        $type = $this->factory->create(RegistrationType::class);

        $type->submit([
            'username' => $username,
            'email' => $email,
            'password' => [
                'first' => $password,
                'second' => $password
            ]
        ]);

        static::assertTrue($type->isValid());
        static::assertTrue($type->isSubmitted());
        static::assertInstanceOf(UserDTO::class, $type->getData());
        static::assertSame($username, $type->getData()->username);
        static::assertSame($email, $type->getData()->email);
        static::assertSame($password, $type->getData()->password);
        static::assertNull($type->getData()->picture->file);
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
