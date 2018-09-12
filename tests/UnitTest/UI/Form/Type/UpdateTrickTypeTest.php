<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\UI\Form\Type;

use App\UI\Form\Subscriber\UpdateTrickSubscriber;
use App\UI\Form\Type\UpdateTrickType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class UpdateTrickTypeTest
 * @package App\Tests\UnitTest\UI\Form\Type
 *
 *
 * TODO
 * At this time. Enable to give more test of this Type containing EntityType
 * because EntityType is managed by DoctrineORMExtension
 * and is not part of the Form Component.
 *
 * This means that the DoctrineORMExtension need the injection of the ManagerRegistry class.
 *
 * https://github.com/symfony/symfony/issues/15098
 */
final class UpdateTrickTypeTest extends TypeTestCase
{
    public function testImplements()
    {
        $subscriber = $this->createMock(UpdateTrickSubscriber::class);

        $type = new UpdateTrickType($subscriber);

        static::assertInstanceOf(AbstractType::class, $type);
        static::assertInstanceOf(UpdateTrickType::class, $type);
    }
}
