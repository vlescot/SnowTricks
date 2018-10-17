<?php
declare(strict_types=1);

namespace Tests\UnitTest\Domain\DTO;

use App\Domain\DTO\VideoDTO;
use PHPUnit\Framework\TestCase;

final class VideoDTOTest extends TestCase
{
    public function testImplements()
    {
        $iFrame = '<iframe width="560" height="315" src="https://www.youtube.com/embed/45cYwDMibGo"></iframe>';

        $dto = new VideoDTO($iFrame);

        static::assertSame($iFrame, $dto->iFrame);
    }
}
