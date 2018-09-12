<?php
declare(strict_types=1);

namespace Tests\UnitTest\Domain\DTO;

use App\Domain\DTO\CommentDTO;
use PHPUnit\Framework\TestCase;

final class CommentDTOTest extends TestCase
{
    /**
     * @param string $content
     *
     * @dataProvider provideData
     */
    public function testImplements(string $content)
    {
        $dto = new CommentDTO($content);

        static::assertSame($content, $dto->content);
    }

    /**
     * @return \Generator
     */
    public function provideData()
    {
        yield ['Lorem ipsum dolor sit amet, consectetur adipisicing elit. Veniam quasi molestias at provident, eos non inventore facere voluptas suscipit. Asperiores nobis dignissimos reprehenderit veritatis officia sequi at accusantium porro veniam.'];
        yield ['A comment'];
    }
}
