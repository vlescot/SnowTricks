<?php
declare(strict_types=1);

namespace Tests\UnitTest\Domain\Entity;

use App\Domain\Entity\Interfaces\PictureInterface;
use App\Domain\Entity\Picture;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;

final class PictureTest extends TestCase
{
    /**
     * @param string $path
     * @param string $filename
     * @param string $alt
     *
     * @dataProvider provideCreatePictureInformation
     */
    public function testImplements(string $path, string $filename, string $alt)
    {
        $picture = new Picture($path, $filename, $alt);

        static::assertInstanceOf(PictureInterface::class, $picture);
        static::assertInstanceOf(UuidInterface::class, $picture->getId());
        static::assertSame($path, $picture->getPath());
        static::assertSame($filename, $picture->getFilename());
        static::assertSame($alt, $picture->getAlt());
        static::assertInternalType('string', $picture->getWebPath());
    }

    /**
     * @param string $path
     *
     * @dataProvider provideUpdatePictureInformation
     */
    public function testUpdateHasGoodValue(string $path)
    {
        $picture = new Picture('trick/trickName/', 'filename.jpg', 'trickname-picturename');
        $picture->update($path); // Used when his directory change when trick's name change

        static::assertSame($path, $picture->getPath());
    }

    /**
     * @return \Generator
     */
    public function provideCreatePictureInformation()
    {
        yield ['user/', 'username.jpg', 'username-picture'];
        yield ['trick/', 'trickName.jpg', 'TrickName-picture'];
    }

    /**
     * @return \Generator
     */
    public function provideUpdatePictureInformation()
    {
        yield ['trick/tricknewname'];
        yield ['trick/tricknewnameagain'];
    }
}
