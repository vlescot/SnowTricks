<?php
declare(strict_types=1);

namespace App\UI\Action\Authentication\Interfaces;

use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\UI\Form\Handler\Interfaces\ChangePasswordHandlerInterface;
use App\UI\Responder\Interfaces\TwigResponderInterface;
use App\UI\Security\LoginFormAuthenticator;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

interface ChangePasswordActionInterface
{
    /**
     * ChangePasswordActionInterface constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param ChangePasswordHandlerInterface $changePasswordHandler
     * @param SessionInterface $session
     * @param UserRepositoryInterface $userRepository
     * @param GuardAuthenticatorHandler $authenticationHandler
     * @param LoginFormAuthenticator $loginAuthenticator
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        ChangePasswordHandlerInterface $changePasswordHandler,
        SessionInterface $session,
        UserRepositoryInterface $userRepository,
        GuardAuthenticatorHandler $authenticationHandler,
        LoginFormAuthenticator $loginAuthenticator
    );

    /**
     * @param Request $request
     * @param TwigResponderInterface $responder
     *
     * @return Response
     */
    public function __invoke(Request $request, TwigResponderInterface $responder): Response;
}
