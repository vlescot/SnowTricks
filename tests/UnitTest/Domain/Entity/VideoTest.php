<?php
declare(strict_types=1);

namespace Tests\UnitTest\Domain\Entity;

use App\Domain\Entity\Interfaces\TrickInterface;
use App\Domain\Entity\Interfaces\VideoInterface;
use App\Domain\Entity\Video;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;

final class VideoTest extends TestCase
{
    /**
     * @param $iFrame
     *
     * @dataProvider provideData
     */
    public function testImplements($iFrame)
    {
        $trickMock = $this->createMock(TrickInterface::class);
        $video = new Video($iFrame);
        $video->setTrick($trickMock);

        static::assertInstanceOf(VideoInterface::class, $video);
        static::assertInstanceOf(UuidInterface::class, $video->getId());
        static::assertSame($iFrame, $video->getIFrame());
        static::assertInstanceOf(TrickInterface::class, $video->getTrick());
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
