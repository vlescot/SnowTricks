<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\UI\Action\Authentication;

use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\UI\Action\Authentication\ChangePasswordAction;
use App\UI\Action\Authentication\Interfaces\ChangePasswordActionInterface;
use App\UI\Form\Handler\Interfaces\ChangePasswordHandlerInterface;
use App\UI\Responder\TwigResponder;
use App\UI\Security\LoginFormAuthenticator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Twig\Environment;

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
     * @var Session
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
        $this->session = $this->createMock(Session::class);
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


    public function testWithGoodUserHandlerReturnTrue()
    {
        $user = $this->createMock(UserInterface::class);
        $this->userRepository->method('loadUserByToken')->willReturn($user);

        $form = $this->createMock(FormInterface::class);
        $form->method('handleRequest')->willReturn($form);
        $this->formFactory->method('create')->willReturn($form);

        $this->changePasswordHandler->method('handle')->willReturn(true);

        $redirectResponse = $this->createMock(RedirectResponse::class);
        $this->authenticationHandler->method('authenticateUserAndHandleSuccess')->willReturn($redirectResponse);


        $request = Request::create('/confirmation/azazaz', 'GET');

        $action = $this->constructInstance();

        $twig = $this->createMock(Environment::class);
        $responder = new TwigResponder($twig);

        $response = $action($request, $responder);


        static::assertInstanceOf(RedirectResponse::class, $response);
        static::assertSame($redirectResponse, $response);
    }


    public function testWithWrongUser()
    {
        $this->userRepository->method('loadUserByToken')->willReturn(null);

        $flashBag = $this->createMock(FlashBagInterface::class);
        $flashBag->method('add')->willReturn(null);
        $this->session->method('getFlashBag')->willReturn($flashBag);

        $form = $this->createMock(FormInterface::class);
        $form->method('handleRequest')->willReturn($form);
        $this->formFactory->method('create')->willReturn($form);

        $this->changePasswordHandler->method('handle')->willReturn(false);

        $twig = $this->createMock(Environment::class);
        $responder = new TwigResponder($twig);

        $request = Request::create('/confirmation/azazaz', 'GET');

        $action = $this->constructInstance();
        $response = $action($request, $responder);

        static::assertInstanceOf(Response::class, $response);
        static::assertNotInstanceOf(RedirectResponse::class, $response);
        static::assertSame(200, $response->getStatusCode());
    }


    public function testWithGoodUserHandlerReturnFalse()
    {
        $user = $this->createMock(UserInterface::class);
        $this->userRepository->method('loadUserByToken')->willReturn($user);

        $form = $this->createMock(FormInterface::class);
        $form->method('handleRequest')->willReturn($form);
        $this->formFactory->method('create')->willReturn($form);

        $this->changePasswordHandler->method('handle')->willReturn(false);

        $twig = $this->createMock(Environment::class);
        $responder = new TwigResponder($twig);

        $request = Request::create('/confirmation/azazaz', 'GET');

        $action = $this->constructInstance();
        $response = $action($request, $responder);

        static::assertInstanceOf(Response::class, $response);
        static::assertNotInstanceOf(RedirectResponse::class, $response);
        static::assertSame(200, $response->getStatusCode());
    }
}
