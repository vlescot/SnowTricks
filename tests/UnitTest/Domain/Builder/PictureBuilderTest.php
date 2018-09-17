<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\Domain\Builder;

use App\Domain\Builder\Interfaces\PictureBuilderInterface;
use App\Domain\Builder\PictureBuilder;
use App\Domain\DTO\PictureDTO;
use App\Domain\Entity\Interfaces\PictureInterface;
use App\Service\Image\Interfaces\ImageThumbnailCreatorInterface;
use App\Service\Image\Interfaces\ImageUploaderInterface;
use App\Service\Image\Interfaces\ImageUploadWarmerInterface;
use PHPUnit\Framework\TestCase;

final class PictureBuilderTest extends TestCase
{
    /**
     * @var ImageUploadWarmerInterface
     */
    private $imageUploadWarmer;

    /**
     * @var ImageUploaderInterface
     */
    private $imageUploader;

    /**
     * @var ImageThumbnailCreatorInterface
     */
    private $thumbnailCreator;

    public function setUp()
    {
        $this->imageUploader = $this->createMock(ImageUploaderInterface::class);
        $this->thumbnailCreator = $this->createMock(ImageThumbnailCreatorInterface::class);
        $this->imageUploadWarmer = $this->createMock(ImageUploadWarmerInterface::class);
    }

    private function constructInstance()
    {
        return new PictureBuilder(
            $this->imageUploader,
            $this->thumbnailCreator,
            $this->imageUploadWarmer
        );
    }

    public function testConstruct()
    {
        $pictureBuilder = $this->constructInstance();

        static::assertInstanceOf(PictureBuilderInterface::class, $pictureBuilder);
    }

    public function testCreateWithPictureDTO()
    {
        $this->imageUploader->method('addFileToUpload')->willReturn(true);
        $this->thumbnailCreator->method('addThumbnailToCreate')->willReturn(true);
        $this->imageUploadWarmer->method('generateImageInfo')->willReturn([
            'path' => 'path/to/file',
            'filename' => 'filename.jpg',
            'alt' => 'subject-filename'
        ]);

        $pictureDTO = new PictureDTO(
            $this->createMock(\SplFileInfo::class)
        );

        $pictureBuilder = $this->constructInstance();

        $picture = $pictureBuilder->create($pictureDTO, false);

        static::assertInstanceOf(PictureInterface::class, $picture);
    }

    public function testCreateWithCollection()
    {
        $this->imageUploader->method('addFileToUpload')->willReturn(true);
        $this->thumbnailCreator->method('addThumbnailToCreate')->willReturn(true);
        $this->imageUploadWarmer->method('generateImageInfo')->willReturn([
            'path' => 'path/to/file',
            'filename' => 'filename.jpg',
            'alt' => 'subject-filename'
        ]);

        $pictureDTO1 = new PictureDTO($this->createMock(\SplFileInfo::class));
        $pictureDTO2 = new PictureDTO($this->createMock(\SplFileInfo::class));
        $pictureDTO3 = new PictureDTO($this->createMock(\SplFileInfo::class));

        $picturesDTO = [
            $pictureDTO1,
            $pictureDTO2,
            $pictureDTO3
        ];

        $pictureBuilder = $this->constructInstance();

        $picturesCollection = $pictureBuilder->create($picturesDTO, true);

        static::assertInternalType('array', $picturesCollection);
        static::assertCount(3, $picturesCollection);
        foreach ($picturesCollection as $picture) {
            static::assertInstanceOf(PictureInterface::class, $picture);
        }
    }
}
