<?php
declare(strict_types=1);

namespace Tests\UnitTest\UI\Form\Type\Authentication;

use App\Domain\DTO\ChangePasswordDTO;
use App\UI\Form\Type\Authentication\ChangePasswordType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Test\TypeTestCase;

final class ChangePasswordTypeTest extends TypeTestCase
{
    public function testImplements()
    {
        $type = new ChangePasswordType();

        static::assertInstanceOf(AbstractType::class, $type);
        static::assertInstanceOf(ChangePasswordType::class, $type);
    }

    /**
     * @param string $password
     *
     * @dataProvider provideCredentials
     */
    public function testSubmitValidData(string $password)
    {
        $type = $this->factory->create(ChangePasswordType::class);

        $type->submit([
            'password' => [
                'first' => $password,
                'second' => $password
            ]
        ]);

        static::assertTrue($type->isValid());
        static::assertTrue($type->isSubmitted());
        static::assertInstanceOf(ChangePasswordDTO::class, $type->getData());
        static::assertSame($password, $type->getData()->password);
    }

    /**
     * @return \Generator
     */
    public function provideCredentials()
    {
        yield ['azerty78'];
        yield ['uhgruhgreohpugr9'];
        yield ['re5hhre'];
    }
}
