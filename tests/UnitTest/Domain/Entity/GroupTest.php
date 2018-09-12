<?php
declare(strict_types=1);

namespace Tests\UnitTest\Domain\Entity;

use App\Domain\Entity\Group;
use App\Domain\Entity\Interfaces\GroupInterface;
use App\Domain\Entity\Interfaces\TrickInterface;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;

final class GroupTest extends TestCase
{
    /**
     * @param string $name
     *
     * @dataProvider provideData
     */
    public function testImplements(string $name)
    {
        $group = new Group($name);

        static::assertInstanceOf(GroupInterface::class, $group);
        static::assertInstanceOf(UuidInterface::class, $group->getId());
        static::assertSame($name, $group->getName());
        static::assertInstanceOf(\ArrayAccess::class, $group->getTricks());
    }

    public function testCollectionReturnGoodValues()
    {
        $trickMock1 = $this->createMock(TrickInterface::class);
        $trickMock2 = $this->createMock(TrickInterface::class);

        $group = new Group('groupName');

        $group->addTrick($trickMock1);
        $group->addTrick($trickMock2);
        static::assertContains($trickMock1, $group->getTricks());
        static::assertContains($trickMock2, $group->getTricks());

        $group->removeTrick($trickMock1);
        static::assertNotContains($trickMock1, $group->getTricks());
        static::assertContains($trickMock2, $group->getTricks());
    }

    public function provideData()
    {
        yield ['Jump'];
        yield ['Slide'];
        yield ['rotation'];
    }
}
