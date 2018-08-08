<?php
declare(strict_types=1);

namespace App\Tests\Domain\Entity;

use PHPUnit\Framework\TestCase;
use App\Domain\Entity\Picture;

class PictureTest extends TestCase
{
    /**
     * @var Picture
     */
    private $picture;

    public function setUp()
    {
        $this->picture = new Picture(
            '/img/trick',
            'filename.jpg',
            'image'
        );
    }

    public function testEntityMustBeInstancied()
    {
        static::assertInstanceOf(Picture::class, $this->picture);
        static::assertObjectHasAttribute('id', $this->picture);
        static::assertObjectHasAttribute('path', $this->picture);
        static::assertObjectHasAttribute('filename', $this->picture);
        static::assertObjectHasAttribute('alt', $this->picture);
        static::assertObjectHasAttribute('trick', $this->picture);
    }

    public function testEntityShouldHaveValidAttributes()
    {
        static::assertContains('/img/trick', $this->picture->getPath());
        static::assertContains('filename.jpg', $this->picture->getFilename());
        static::assertContains('image', $this->picture->getAlt());
    }
}
