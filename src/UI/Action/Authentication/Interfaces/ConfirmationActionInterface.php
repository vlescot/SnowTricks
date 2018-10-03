<?php
declare(strict_types=1);

namespace App\UI\Action\Authentication\Interfaces;

use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\UI\Responder\Authentication\Interfaces\ConfirmationResponderInterface;
use App\App\Security\LoginFormAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

interface ConfirmationActionInterface
{
    /**
     * ConfirmationActionInterface constructor.
     *
     * @param UserRepositoryInterface $userRepository
     * @param GuardAuthenticatorHandler $authenticationHandler
     * @param LoginFormAuthenticator $loginAuthenticator
     * @param SessionInterface $session
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        GuardAuthenticatorHandler $authenticationHandler,
        LoginFormAuthenticator $loginAuthenticator,
        SessionInterface $session,
        UrlGeneratorInterface $urlGenerator
    );

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request): Response;
}
