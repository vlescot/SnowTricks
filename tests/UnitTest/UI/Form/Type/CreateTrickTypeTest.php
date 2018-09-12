<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\UI\Form\Type;

use App\UI\Form\Type\CreateTrickType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class CreateTrickTypeTest
 * @package App\Tests\UnitTest\UI\Form\Type
 *
 *
 * TODO
 * At this time. Enable to give more of this Type containing EntityType
 * because EntityType is managed by DoctrineORMExtension
 * and is not part of the Form Component.
 *
 * This means that the DoctrineORMExtension need the injection of the ManagerRegistry class.
 *
 * https://github.com/symfony/symfony/issues/15098
 */
final class CreateTrickTypeTest extends TypeTestCase
{
    public function testImplements()
    {
        $type = new CreateTrickType();

        static::assertInstanceOf(AbstractType::class, $type);
        static::assertInstanceOf(CreateTrickType::class, $type);
    }
}