<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\Domain\Builder;

use App\Domain\Builder\GroupBuilder;
use App\Domain\DTO\GroupDTO;
use App\Domain\Entity\Interfaces\GroupInterface;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

final class GroupBuilderTest extends TestCase
{
    public function testCreateFunctionReturnsCollectionOfGroups()
    {
        $group1 = $this->createMock(GroupInterface::class);
        $group2 = $this->createMock(GroupInterface::class);

        $collection = new ArrayCollection();
        $collection->add($group1);
        $collection->add($group2);

        $groupsDTO = [
            new GroupDTO('Jump'),
            new GroupDTO('Slide'),
            new GroupDTO('Rotation'),
        ];


        $groupBuilder = new GroupBuilder();

        $groupsCollection = $groupBuilder->create($collection, $groupsDTO);


        static::assertInstanceOf(\ArrayAccess::class, $groupsCollection);
        static::assertCount(5, $groupsCollection);
        foreach ($groupsCollection->getIterator() as $group) {
            static::assertInstanceOf(GroupInterface::class, $group);
        }
    }
}
