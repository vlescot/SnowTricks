<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\UI\Action\Authentication;

use App\Domain\Entity\User;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\UI\Action\Authentication\ConfirmationAction;
use App\UI\Action\Authentication\Interfaces\ConfirmationActionInterface;
use App\App\Security\LoginFormAuthenticator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

final class ConfirmationActionTest extends TestCase
{
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

    /**
     * @var Session
     */
    private $session;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;


    public function setUp()
    {
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->authenticationHandler = $this->createMock(GuardAuthenticatorHandler::class);
        $this->loginAuthenticator = $this->createMock(LoginFormAuthenticator::class);
        $this->session = $this->createMock(Session::class);
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);
    }

    private function constructInstance()
    {
        return new ConfirmationAction(
            $this->userRepository,
            $this->authenticationHandler,
            $this->loginAuthenticator,
            $this->session,
            $this->urlGenerator
        );
    }


    public function testConstruct()
    {
        $action = $this->constructInstance();

        static::assertInstanceOf(ConfirmationActionInterface::class, $action);
    }


    public function testWithGoodUserGiven()
    {
        $user = $this->getMockBuilder(UserInterface::class)
            ->setMethods(['setConfirmation', 'getRoles', 'getPassword', 'getSalt', 'getUsername', 'eraseCredentials'])
            ->getMock();
        $user->method('setConfirmation')->willReturn(null);

        $this->userRepository->method('loadUserByToken')->willReturn($user);
        $this->userRepository->method('save')->willReturn(null);

        $this->authenticationHandler->method('authenticateUserAndHandleSuccess')->willReturn(
            new Response('OK', 200)
        );

        $request = Request::create('/confirmation/azaz', 'GET');

        $action = $this->constructInstance();
        $response = $action($request);

        static::assertNotInstanceOf(RedirectResponse::class, $response);
        static::assertInstanceOf(Response::class, $response);
    }



    public function testWithWrongUserGiven()
    {
        $flashBag = $this->createMock(FlashBagInterface::class);
        $flashBag->method('add')->willReturn(null);
        $this->session->method('getFlashBag')->willReturn($flashBag);

        $this->urlGenerator->method('generate')->willReturn('/');

        $this->userRepository->method('loadUserByToken')->willReturn(null);


        $request = Request::create('/confirmation/azazaz', 'GET');

        $action = $this->constructInstance();
        $response = $action($request);

        static::assertInstanceOf(RedirectResponse::class, $response);
    }
}
