<?php
declare(strict_types=1);

namespace Tests\UnitTest\UI\Form\Type;

use App\Domain\DTO\GroupDTO;
use App\UI\Form\Type\GroupType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Test\TypeTestCase;

final class GroupTypeTest extends TypeTestCase
{
    public function testImplements()
    {
        $type = new GroupType();

        static::assertInstanceOf(AbstractType::class, $type);
        static::assertInstanceOf(GroupType::class, $type);
    }

    /**
     * @param string $name
     *
     * @dataProvider provideData
     */
    public function testSubmitValidData(string $name)
    {
        $type = $this->factory->create(GroupType::class);

        $type->submit(['name' => $name]);

        static::assertTrue($type->isValid());
        static::assertTrue($type->isSubmitted());
        static::assertInstanceOf(GroupDTO::class, $type->getData());
        static::assertSame($name, $type->getData()->name);
    }

    /**
     * @return \Generator
     */
    public function provideData()
    {
        yield ['Group'];
        yield ['Another Group'];
    }
}
