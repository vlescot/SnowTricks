<?php
declare(strict_types=1);

namespace App\Tests\Domain\Entity;

use App\Domain\Entity\Comment;
use App\Domain\Entity\Group;
use App\Domain\Entity\Picture;
use App\Domain\Entity\User;
use App\Domain\Entity\Video;
use PHPUnit\Framework\TestCase;
use App\Domain\Entity\Trick;

class TrickTest extends TestCase
{
    /**
     * @var Trick
     */
    private $trick;

    public function setUp()
    {
        $authorMock = $this->createMock(User::class);
        $pictureMock = $this->createMock(Picture::class);

        $trick = new Trick();
        $trick->create(
            'Trick Title',
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut luctus elit vitae tortor rutrum, at dignissim libero finibus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.',
            $authorMock,
            $pictureMock
        );

        $this->trick = $trick;
    }

    public function testEntityMustBeInstancied()
    {
        static::assertInstanceOf(Trick::class, $this->trick);
        static::assertObjectHasAttribute('id', $this->trick);
        static::assertObjectHasAttribute('title', $this->trick);
        static::assertObjectHasAttribute('slug', $this->trick);
        static::assertObjectHasAttribute('description', $this->trick);
        static::assertObjectHasAttribute('createdAt', $this->trick);
        static::assertObjectHasAttribute('updatedAt', $this->trick);
        static::assertObjectHasAttribute('author', $this->trick);
        static::assertObjectHasAttribute('mainPicture', $this->trick);
        static::assertObjectHasAttribute('pictures', $this->trick);
        static::assertObjectHasAttribute('videos', $this->trick);
        static::assertObjectHasAttribute('groups', $this->trick);
        static::assertObjectHasAttribute('comments', $this->trick);
    }

    public function testEntityShouldHaveValidAttributes()
    {
        static::assertContains('Trick Title', $this->trick->getTitle());
        static::assertContains('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut luctus elit vitae tortor rutrum, at dignissim libero finibus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.', $this->trick->getDescription());
    }
}
