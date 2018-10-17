<?php
declare(strict_types=1);

namespace Tests\UnitTest\Domain\Entity;

use App\Domain\Entity\Picture;
use App\Domain\Entity\User;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserTest extends TestCase
{
    /**
     * @param string $username
     * @param string $email
     * @param string $password
     *
     * @dataProvider provideRegistrationCredentials
     */
    public function testImplements(string $username, string $email, string $password)
    {
        $user  = new User();
        $user->registration($username, $email, $password);

        static::assertInstanceOf(UserInterface::class, $user);
        static::assertInstanceOf(UuidInterface::class, $user->getId());
        static::assertInternalType('int', $user->getCreatedAt());
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     *
     * @dataProvider provideRegistrationCredentials
     */
    public function testUserRegistrationImplements(string $username, string $email, string $password)
    {
        $user  = new User();
        $user->registration($username, $email, $password);

        static::assertInstanceOf(UserInterface::class, $user);
        static::assertInstanceOf(UuidInterface::class, $user->getId());
        static::assertSame($user->getUsername(), $username);
        static::assertSame($user->getEmail(), $email);
        static::assertSame($user->getPassword(), $password);
        static::assertInternalType('int', $user->getCreatedAt());
        static::assertInternalType('string', $user->getToken());
        static::assertInternalType('array', $user->getRoles());
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     *
     * @dataProvider provideRegistrationCredentials
     */
    public function testUserConfirmationHasGoodRoles(string $username, string $email, string $password)
    {
        $user  = new User();
        $user->registration($username, $email, $password);
        $user->setConfirmation(true);

        static::assertSame(['ROLE_USER'], $user->getRoles());
    }

    /**
     * @param string $email
     * @param string $password
     *
     * @dataProvider provideUpdateCredentials
     */
    public function testUpdateUserImplements(string $email, string $password)
    {
        $picture = $this->createMock(Picture::class);

        $user = new User();
        $user->registration('Toto', 'toto@mail.com', 'azerty78');
        $user->setConfirmation(true);

        $user->update($email, $password, $picture);

        static::assertSame($email, $user->getEmail());
        static::assertSame($password, $user->getPassword());
        static::assertSame($picture, $user->getPicture());
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     *
     * @dataProvider provideRegistrationCredentials
     */
    public function testProfilePictureCreation(string $username, string $email, string $password)
    {
        $picture = $this->createMock(Picture::class);

        $user = new User();
        $user->registration($username, $email, $password, $picture);

        static::assertInstanceOf(Picture::class, $user->getPicture());
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $password
     *
     * @dataProvider provideRegistrationCredentials
     */
    public function testUserWithoutPersonalPictureReturnDefaultPicture(string $username, string $email, string $password)
    {
        $user = new User();
        $user->registration($username, $email, $password);

        static::assertInstanceOf(Picture::class, $user->getPicture());
        static::assertSame('/image/user/', $user->getPicture()->getPath());
        static::assertSame('user_default.png', $user->getPicture()->getFilename());
        static::assertSame('default-member-picture', $user->getPicture()->getAlt());
    }

    /**
     * @return \Generator
     */
    public function provideRegistrationCredentials()
    {
        yield ['Toto', 'toto@mail.com', 'azerty78'];
        yield ['Toti', 'toti@mail.com', 'uhgruhgreohpugr9'];
        yield ['Tata', 'tata@mail.com', 're5hhre'];
    }

    public function provideUpdateCredentials()
    {
        yield ['newEmail@mail.com', 'ne?Passw0rd'];
        yield ['email@mail.com', 'P755W0?d'];
    }
}
