<?php
declare(strict_types=1);

namespace Tests\UnitTest\UI\Form\Type;

use App\Domain\DTO\VideoDTO;
use App\UI\Form\Type\VideoType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Test\TypeTestCase;

final class VideoTypeTest extends TypeTestCase
{
    public function testImplements()
    {
        $type = new VideoType();

        static::assertInstanceOf(AbstractType::class, $type);
        static::assertInstanceOf(VideoType::class, $type);
    }

    /**
     * @param string $iFrame
     *
     * @dataProvider provideData
     */
    public function testSubmitValidData(string $iFrame)
    {
        $type = $this->factory->create(VideoType::class);

        $type->submit(['iFrame' => $iFrame]);

        static::assertTrue($type->isValid());
        static::assertTrue($type->isSubmitted());
        static::assertInstanceOf(VideoDTO::class, $type->getData());
        static::assertSame($iFrame, $type->getData()->iFrame);
    }

    /**
     * @return \Generator
     */
    public function provideData()
    {
        yield ['<iframe width="560" height="315" src="https://www.youtube.com/embed/Qyclqo_AV2M" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>'];
        yield ['<iframe width="560" height="315" src="https://www.youtube.com/embed/45cYwDMibGo"></iframe>'];
    }
}
