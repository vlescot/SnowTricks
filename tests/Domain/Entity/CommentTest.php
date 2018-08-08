<?php
declare(strict_types=1);

namespace App\Domain\Entity;

use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
    /**
     * @var Comment
     */
    private $comment;


    public function setUp()
    {
        $user = $this->createMock(User::class);
        $trick = $this->createMock(Trick::class);

        $this->comment = new Comment(
            'A comment',
            $user,
            $trick
        );
    }


    public function testEntityMustBeInstancied()
    {
        static::assertInstanceOf(Comment::class, $this->comment);
        static::assertObjectHasAttribute('id', $this->comment);
        static::assertObjectHasAttribute('content', $this->comment);
        static::assertObjectHasAttribute('createdAt', $this->comment);
        static::assertObjectHasAttribute('author', $this->comment);
        static::assertObjectHasAttribute('trick', $this->comment);
    }

    public function testEntityShouldHaveValidAttributes()
    {
        static::assertContains('A comment', $this->comment->getContent());
    }
}
