<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\Domain\Builder;

use App\Domain\Builder\VideoBuilder;
use App\Domain\DTO\VideoDTO;
use App\Domain\Entity\Interfaces\VideoInterface;
use Symfony\Bundle\WebProfilerBundle\Tests\TestCase;

final class VideoBuilderTest extends TestCase
{
    public function testCreateFunctionReturnsVideoInterface()
    {
        $iFrame = '<iframe width="560" height="315" src="https://www.youtube.com/embed/Qyclqo_AV2M" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>';

        $videoDTO = new VideoDTO($iFrame);

        $videoBuilder = new VideoBuilder();

        $video = $videoBuilder->create($videoDTO, false);


        static::assertInstanceOf(VideoInterface::class, $video);
        static::assertSame($iFrame, $video->getIFrame());
    }


    public function testCreateFunctionReturnsCollectionOfVideo()
    {
        $videosDTO = [
            new VideoDTO('<iframe width="560" height="315" src="https://www.youtube.com/embed/Qyclqo_AV2M" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>'),
            new VideoDTO('<iframe width="560" height="315" src="https://www.youtube.com/embed/45cYwDMibGo"></iframe>'),
            new VideoDTO('<iframe width="560" height="315" src="https://www.youtube.com/embed/45cYwDMibGo"></iframe>'),
        ];

        $videoBuilder = new VideoBuilder();

        $videosCollection = $videoBuilder->create($videosDTO, true);


        static::assertInternalType('array', $videosCollection);
        static::assertCount(3, $videosCollection);
        foreach ($videosCollection as $video) {
            static::assertInstanceOf(VideoInterface::class, $video);
        }
    }
}
