<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\UI\Action\Authentication;

use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\UI\Action\Authentication\ChangePasswordAction;
use App\UI\Action\Authentication\Interfaces\ChangePasswordActionInterface;
use App\UI\Form\Handler\Interfaces\ChangePasswordHandlerInterface;
use App\UI\Security\LoginFormAuthenticator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

final class ChangePasswordActionTest extends TestCase
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var ChangePasswordHandlerInterface
     */
    private $changePasswordHandler;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var GuardAuthenticatorHandler
     */
    private $authenticationHandler;

    /**
     * @var LoginFormAuthenticator
     */
    private $loginAuthenticator;


    public function setUp()
    {
        $this->formFactory = $this->createMock(FormFactoryInterface::class);
        $this->changePasswordHandler = $this->createMock(ChangePasswordHandlerInterface::class);
        $this->session = $this->createMock(SessionInterface::class);
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->authenticationHandler = $this->createMock(GuardAuthenticatorHandler::class);
        $this->loginAuthenticator = $this->createMock(LoginFormAuthenticator::class);
    }

    public function constructInstance()
    {
        return new ChangePasswordAction(
            $this->formFactory,
            $this->changePasswordHandler,
            $this->session,
            $this->userRepository,
            $this->authenticationHandler,
            $this->loginAuthenticator
        );
    }


    public function testConstruct()
    {
        $action = $this->constructInstance();

        static::assertInstanceOf(ChangePasswordActionInterface::class, $action);
    }

    // TODO AUTHENTICATION return $this->authenticationHandler->authenticateUserAndHandleSuccess()
//    public function testReturnGoodValue()
//    {
//        $this->userRepository->method('loadUserByToken')->willReturn(
//            $this->createMock(UserInterface::class)
//        );
//
//        $this->formFactory->method('create')->willReturn(
//            $this->createMock(FormInterface::class)
//        );
//
//        $form = $this->createMock(FormInterface::class);
//        $form->method('handleRequest')->willReturn($form);
//
//        $this->formFactory->method('create')->willReturn($form);
//
//        $this->changePasswordHandler->method('handle')->willReturn(true);
//    }
}
