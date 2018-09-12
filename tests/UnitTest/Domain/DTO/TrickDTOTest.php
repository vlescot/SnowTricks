<?php
declare(strict_types=1);

namespace Tests\UnitTest\Domain\DTO;

use App\Domain\DTO\Interfaces\GroupDTOInterface;
use App\Domain\DTO\Interfaces\PictureDTOInterface;
use App\Domain\DTO\Interfaces\VideoDTOInterface;
use App\Domain\DTO\TrickDTO;
use PHPUnit\Framework\TestCase;

final class TrickDTOTest extends TestCase
{
    public function testHasGoodValues()
    {
        $title = 'Spins';
        $description = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sit ipsam eum in repudiandae libero ducimus, ratione dolorum, adipisci nemo exercitationem soluta praesentium aliquam harum! Adipisci facilis sequi quae in deserunt.';

        $mainPicture = $this->createMock(PictureDTOInterface::class);
        $pictures = [
            $this->createMock(PictureDTOInterface::class),
            $this->createMock(PictureDTOInterface::class)
        ];
        $videos = [
            $this->createMock(VideoDTOInterface::class),
            $this->createMock(VideoDTOInterface::class)
        ];
        $groups = $this->createMock(\ArrayAccess::class);
        $newGroups = [
            $this->createMock(GroupDTOInterface::class),
            $this->createMock(GroupDTOInterface::class)
        ];

        $dto = new TrickDTO($title, $description, $mainPicture, $pictures, $videos, $groups, $newGroups);

        static::assertSame($title, $dto->title);
        static::assertSame($description, $dto->description);
        static::assertSame($mainPicture, $dto->mainPicture);
        static::assertSame($pictures, $dto->pictures);
        static::assertSame($videos, $dto->videos);
        static::assertSame($groups, $dto->groups);
        static::assertSame($newGroups, $dto->newGroups);
    }
}
