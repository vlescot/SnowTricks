<?php
declare(strict_types=1);

namespace Tests\UnitTest\UI\Form\Type;

use App\Domain\DTO\Interfaces\PictureDTOInterface;
use App\UI\Form\Type\PictureType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class PictureTypeTest extends TypeTestCase
{
    public function testImplements()
    {
        $type = new PictureType();

        static::assertInstanceOf(AbstractType::class, $type);
        static::assertInstanceOf(PictureType::class, $type);
    }

    public function testSubmitValidData()
    {
        $pictureMock = $this->createMock(UploadedFile::class);

        $type = $this->factory->create(PictureType::class);

        $type->submit(['file' => $pictureMock]);

        static::assertTrue($type->isValid());
        static::assertTrue($type->isSubmitted());
        static::assertInstanceOf(PictureDTOInterface::class, $type->getData());
    }
}
