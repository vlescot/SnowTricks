<?php
declare(strict_types=1);

namespace App\Tests\Domain\DTO;

use App\Domain\Entity\Trick;
use App\Domain\Entity\User;
use PHPUnit\Framework\TestCase;
use App\Domain\DTO\CommentDTO;

class CommentDTOTest extends TestCase
{
    /**
     * @var CommentDTO
     */
    private $dto;

    public function setUp()
    {
        $authorMock = $this->createMock(User::class);
        $trickMock = $this->createMock(Trick::class);

        $this->dto = new CommentDTO('A comment');
    }

    public function testCommentDTOAttributesMustBeValid()
    {
        static::assertInternalType('string', $this->dto->content);
    }
}
