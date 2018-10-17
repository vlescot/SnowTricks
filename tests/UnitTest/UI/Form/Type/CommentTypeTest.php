<?php
declare(strict_types=1);

namespace Tests\UnitTest\UI\Form\Type;

use App\Domain\DTO\CommentDTO;
use App\UI\Form\Type\CommentType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Test\TypeTestCase;

final class CommentTypeTest extends TypeTestCase
{
    public function testImplements()
    {
        $type = new CommentType();

        static::assertInstanceOf(AbstractType::class, $type);
        static::assertInstanceOf(CommentType::class, $type);
    }

    /**
     * @param string $content
     *
     * @dataProvider provideData
     */
    public function testSubmitValidData(string $content)
    {
        $type = $this->factory->create(CommentType::class);

        $type->submit(['content' => $content]);

        static::assertTrue($type->isValid());
        static::assertTrue($type->isSubmitted());
        static::assertInstanceOf(CommentDTO::class, $type->getData());
        static::assertSame($content, $type->getData()->content);
    }

    /**
     * @return \Generator
     */
    public function provideData()
    {
        yield ['Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eum ipsa dolorem architecto porro beatae, quo, quaerat numquam atque veniam totam consequatur nostrum, incidunt sit, vitae voluptates iusto commodi aliquid id.'];
        yield ['Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dignissimos minima totam vitae delectus, odit eaque ab! Mollitia sapiente tempora ipsa cumque ratione. Architecto molestiae, facilis ipsa. Tempora ea, a. Cupiditate.'];
        yield ['Tata'];
    }
}
