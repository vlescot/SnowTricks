<?php
declare(strict_types=1);

namespace App\Tests\Domain\Entity;

use PHPUnit\Framework\TestCase;
use App\Domain\Entity\Video;

class VideoTest extends TestCase
{
    /**
     * @var Video
     */
    private $video;

    public function setUp()
    {
        $this->video = new Video(
            '<iframe width="560" height="315" src="https://www.youtube.com/embed/Qyclqo_AV2M" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>'
        );
    }

    public function testEntityMustBeInstancied()
    {
        static::assertInstanceOf(Video::class, $this->video);
        static::assertObjectHasAttribute('id', $this->video);
        static::assertObjectHasAttribute('iFrame', $this->video);
        static::assertObjectHasAttribute('trick', $this->video);
    }

    public function testEntityShouldHaveValidAttributes()
    {
        static::assertContains('<iframe width="560" height="315" src="https://www.youtube.com/embed/Qyclqo_AV2M" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>', $this->video->getIFrame());
    }
}
