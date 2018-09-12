<?php
declare(strict_types=1);

namespace App\UI\Action\Authentication;

use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\UI\Action\Authentication\Interfaces\ConfirmationActionInterface;
use App\UI\Responder\Authentication\Interfaces\ConfirmationResponderInterface;
use App\UI\Security\LoginFormAuthenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

/**
 * @Route(
 *     "/confirmation/{token}",
 *     name = "UserConfirmation",
 *     methods = {"GET"}
 * )
 *
 * Class ConfirmationAction
 * @package App\UI\Action\Authentication
 *
 * This class set the user's confirmation
 * via modal windows (only one route manage all the modals windows).
 * Then, the forms handling are managed with the AuthenticationViewAction
 */
final class ConfirmationAction implements ConfirmationActionInterface
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

    /**
     * ConfirmationAction constructor.
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
    ) {
        $this->userRepository = $userRepository;
        $this->authenticationHandler = $authenticationHandler;
        $this->loginAuthenticator = $loginAuthenticator;
        $this->session = $session;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $token = $request->attributes->get('token');

        $user = $this->userRepository->loadUserByToken($token);

        if (null !== $user) {
            $user->setConfirmation(true);
            $this->userRepository->save($user);

            return $this->authenticationHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $this->loginAuthenticator,
                'main'
            );
        }

        $this->session->getFlashBag()->add('danger', 'Nous n\'avons pas pu vous authentifier');

        return new RedirectResponse($this->urlGenerator->generate('Home'));
    }
}
