<?php
declare(strict_types=1);

namespace Tests\UnitTest\Domain\Entity;

use App\Domain\Entity\Comment;
use App\Domain\Entity\Interfaces\CommentInterface;
use App\Domain\Entity\Interfaces\TrickInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class CommentTest extends TestCase
{
    /**
     * @param string $content
     *
     * @dataProvider provideData
     */
    public function testImplements(string $content)
    {
        $trickMock = $this->createMock(TrickInterface::class);
        $authorMock = $this->createMock(UserInterface::class);

        $comment = new Comment($content, $authorMock, $trickMock);

        static::assertInstanceOf(CommentInterface::class, $comment);
        static::assertInstanceOf(UuidInterface::class, $comment->getId());
        static::assertInternalType('int', $comment->getCreatedAt());
        static::assertSame($content, $comment->getContent());
        static::assertSame($authorMock, $comment->getAuthor());
        static::assertSame($trickMock, $comment->getTrick());
    }

    public function provideData()
    {
        yield ['Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatibus sunt vel minus et repudiandae distinctio expedita voluptate, sint accusantium illum odio ipsum nisi. Culpa quis, fugiat ipsam iure, numquam blanditiis?'];
        yield ['A comment'];
    }
}
