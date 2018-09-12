<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\UI\Action\Authentication;

use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\UI\Action\Authentication\ConfirmationAction;
use App\UI\Action\Authentication\Interfaces\ConfirmationActionInterface;
use App\UI\Security\LoginFormAuthenticator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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
     * @var SessionInterface
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
        $this->session = $this->createMock(SessionInterface::class);
        $this->urlGenerator = $this->createMock(UrlGeneratorInterface::class);
    }

    public function testConstruct()
    {
        $action =  new ConfirmationAction(
            $this->userRepository,
            $this->authenticationHandler,
            $this->loginAuthenticator,
            $this->session,
            $this->urlGenerator
        );

        static::assertInstanceOf(ConfirmationActionInterface::class, $action);
    }

    // TODO return $this->authenticationHandler->authenticateUserAndHandleSuccess()
//    public function testReturnGoodResponse()
//    {
//
//    }
}
