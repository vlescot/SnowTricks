<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\Domain\Factory;

use App\Domain\DTO\Interfaces\PictureDTOInterface;
use App\Domain\Entity\Picture;
use App\Domain\Factory\PictureDTOFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File;

final class PictureDTOFactoryTest extends TestCase
{
    public function testReturnGoodValue()
    {
        $picture = new Picture(
            '/image/tests',
            'r1.png',
            'image-test'
        );

        $factory = new PictureDTOFactory();
        $pictureDTO = $factory->create($picture);

        static::assertInstanceOf(PictureDTOInterface::class, $pictureDTO);
        static::assertInstanceOf(File::class, $pictureDTO->file);
    }
}
