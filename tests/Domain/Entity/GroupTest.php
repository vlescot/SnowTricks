<?php
declare(strict_types=1);

namespace App\Tests\Domain\Entity;

use PHPUnit\Framework\TestCase;
use App\Domain\Entity\Group;

class GroupTest extends TestCase
{
    /**
     * @var Group
     */
    private $group;

    public function setUp()
    {
        $this->group = new Group('Jump');
    }

    public function testEntityMustBeInstancied()
    {
        static::assertInstanceOf(Group::class, $this->group);
        static::assertObjectHasAttribute('id', $this->group);
        static::assertObjectHasAttribute('name', $this->group);
        static::assertObjectHasAttribute('tricks', $this->group);
    }

    public function testEntityShouldHaveValidAttributes()
    {
        static::assertContains('Jump', $this->group->getName());
    }
}
