<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\Domain\Factory;

use App\Domain\DTO\Interfaces\VideoDTOInterface;
use App\Domain\Entity\Video;
use App\Domain\Factory\VideoDTOFactory;
use PHPUnit\Framework\TestCase;

final class VideoDTOFactoryTest extends TestCase
{
    public function testReturnGoodValue()
    {
        $iFrame = '<iframe src="https://www.youtube.com/embed/Qyclqo_AV2M"></iframe>';
        $video = new Video($iFrame);

        $videoFactory = new VideoDTOFactory();
        $videoDTO = $videoFactory->create($video);

        static::assertInstanceOf(VideoDTOInterface::class, $videoDTO);
        static::assertSame($iFrame, $videoDTO->iFrame);
    }
}
