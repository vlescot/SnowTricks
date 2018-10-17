<?php
declare(strict_types=1);

namespace Tests\UnitTest\UI\Form\Type\Authentication;

use App\UI\Form\Type\Authentication\ResetPasswordType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Test\TypeTestCase;

final class ResetPasswordTypeTest extends TypeTestCase
{
    public function testImplements()
    {
        $type = new ResetPasswordType();

        static::assertInstanceOf(AbstractType::class, $type);
        static::assertInstanceOf(ResetPasswordType::class, $type);
    }

    /**
     * @param string $username
     *
     * @dataProvider provideCredentials
     */
    public function testSubmitValidData(string $username)
    {
        $type = $this->factory->create(ResetPasswordType::class);

        $type->submit(['username' => $username]);

        static::assertTrue($type->isValid());
        static::assertTrue($type->isSubmitted());
        static::assertSame($username, $type->getData()['username']);
    }

    /**
     * @return \Generator
     */
    public function provideCredentials()
    {
        yield ['azerty78'];
        yield ['toto@gmail.com'];
        yield ['re5hhre'];
    }
}
