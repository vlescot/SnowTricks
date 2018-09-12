<?php
declare(strict_types=1);

namespace Tests\UnitTest\Domain\DTO;

use App\Domain\DTO\PictureDTO;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class PictureDTOTest extends TestCase
{
    public function testImplements()
    {
        $file = $this->createMock(UploadedFile::class);

        $dto = new PictureDTO($file);

        static::assertSame($file, $dto->file);
    }
}
