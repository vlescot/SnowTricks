<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\UI\Form\Handler;

use App\App\Interfaces\MailerInterface;
use App\UI\Form\Handler\Interfaces\ResetPasswordHandlerInterface;
use App\UI\Form\Handler\ResetPasswordHandler;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class ResetPasswordHandlerTest extends KernelTestCase
{
    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var MailerInterface
     */
    private $mailer;

    public function setUp()
    {
        static::bootKernel();

        $this->userProvider = $this->createMock(UserProviderInterface::class);
        $this->session = $this::$kernel->getContainer()->get('session');
        $this->mailer = $this->createMock(MailerInterface::class);
    }

    private function constructInstance()
    {
        return new ResetPasswordHandler(
            $this->userProvider,
            $this->session,
            $this->mailer
        );
    }


    public function testConstruct()
    {
        $resetPasswordHandler = $this->constructInstance();

        static::assertInstanceOf(ResetPasswordHandlerInterface::class, $resetPasswordHandler);
    }


    public function testHandleReturnTrue()
    {
        $form = $this->createMock(FormInterface::class);
        $form->method('isSubmitted')->willReturn(true);
        $form->method('isValid')->willReturn(true);
        $form->method('get')->willReturn($form);
        $form->method('getData')->willReturn('username');

        $user = $this->getMockBuilder(UserInterface::class)
            ->setMethods(['getEmail', 'getToken', 'getRoles', 'getPassword', 'getSalt', 'getUsername', 'eraseCredentials'])
            ->getMock();
        $user->method('getEmail')->willReturn('username@mail.com');
        $user->method('getToken')->willReturn(md5('alphabet'));

        $this->userProvider->method('loadUserByUsername')->willReturn($user);
        $this->mailer->method('sendMail')->willReturn(true);


        $resetPasswordHandler = $this->constructInstance();

        static::assertTrue($resetPasswordHandler->handle($form));
    }


    public function testHandleReturnFalse()
    {
        $form = $this->createMock(FormInterface::class);
        $form->method('isSubmitted')->willReturn(false);
        $form->method('isValid')->willReturn(false);

        $resetPasswordHandler = $this->constructInstance();

        static::assertFalse($resetPasswordHandler->handle($form));
    }
}
