<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\UI\Form\Type\Authentication;

use App\UI\Form\Type\Authentication\LoginType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Test\TypeTestCase;

final class LoginTypeTest extends TypeTestCase
{
    public function testImplements()
    {
        $type = new LoginType();

        static::assertInstanceOf(AbstractType::class, $type);
        static::assertInstanceOf(LoginType::class, $type);
    }

    public function testSubmitValidData()
    {
        $formData = [
            '_login' => 'username',
            '_password' => 'pa55w0rd'
        ];

        $type = $this->factory->create(LoginType::class);
        $type->submit($formData);

        $this->assertTrue($type->isSynchronized());

        $view = $type->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
