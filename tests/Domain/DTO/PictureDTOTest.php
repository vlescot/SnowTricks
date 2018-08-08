<?php
declare(strict_types=1);

namespace App\Tests\Domain\DTO;

use PHPUnit\Framework\TestCase;
use App\Domain\DTO\PictureDTO;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureDTOTest extends TestCase
{
    /**
     * @var PictureDTO
     */
    private $dto;

    public function setUp()
    {
        $fileMock = $this->createMock(UploadedFile::class);

        $this->dto = new PictureDTO($fileMock);
    }

    public function testPictureDTOAttributeMustBeAnImageFile()
    {
        static::assertInstanceOf(UploadedFile::class, $this->dto->file);
    }
}
