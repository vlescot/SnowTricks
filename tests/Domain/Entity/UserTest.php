<?php
declare(strict_types=1);

namespace App\Tests\Domain\Entity;

use App\Domain\Entity\Picture;
use PHPUnit\Framework\TestCase;
use App\Domain\Entity\User;

class UserTest extends TestCase
{
    /**
     * @var User
     */
    private $user;

    public function setUp()
    {
        $pictureMock = $this->createMock(Picture::class);

        $user = new User();
        $user->registration(
            'Floyd',
            'floyd@mail.com',
            'strongpassword',
            $pictureMock
        );

        $this->user = $user;
    }

    public function testEntityMustBeInstancied()
    {
        static::assertInstanceOf(User::class, $this->user);
        static::assertObjectHasAttribute('id', $this->user);
        static::assertObjectHasAttribute('username', $this->user);
        static::assertObjectHasAttribute('password', $this->user);
        static::assertObjectHasAttribute('plainPassword', $this->user);
        static::assertObjectHasAttribute('email', $this->user);
        static::assertObjectHasAttribute('createdAt', $this->user);
        static::assertObjectHasAttribute('token', $this->user);
        static::assertObjectHasAttribute('roles', $this->user);
        static::assertObjectHasAttribute('enabled', $this->user);
        static::assertObjectHasAttribute('picture', $this->user);
        static::assertObjectHasAttribute('comments', $this->user);
        static::assertObjectHasAttribute('tricks', $this->user);
    }

    public function testEntityShouldHaveValidAttributes()
    {
        static::assertContains('Floyd', $this->user->getUsername());
        static::assertContains('floyd@mail.com', $this->user->getEmail());
        static::assertContains('strongpassword', $this->user->getPassword());
    }
}
