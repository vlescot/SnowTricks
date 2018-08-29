<?php
declare(strict_types=1);

namespace App\UI\Action\Authentication;

use App\Domain\Repository\UserRepository;
use App\UI\Responder\Authentication\ConfirmationResponder;
use App\UI\Security\LoginFormAuthenticator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

/**
 * @Route("/confirmation/{token}", name="UserConfirmation")
 * @Method("GET")
 *
 * Class ConfirmationAction
 * @package App\UI\Action\Authentication
 */
class ConfirmationAction
{
    /**
     * @var UserRepository
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
     * ConfirmationAction constructor.
     *
     * @param UserRepository $userRepository
     * @param GuardAuthenticatorHandler $authenticationHandler
     * @param LoginFormAuthenticator $loginAuthenticator
     * @param SessionInterface $session
     */
    public function __construct(
        UserRepository $userRepository,
        GuardAuthenticatorHandler $authenticationHandler,
        LoginFormAuthenticator $loginAuthenticator,
        SessionInterface $session
    ) {
        $this->userRepository = $userRepository;
        $this->authenticationHandler = $authenticationHandler;
        $this->loginAuthenticator = $loginAuthenticator;
        $this->session = $session;
    }

    /**
     * @param Request $request
     * @param $response
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function __invoke(Request $request, ConfirmationResponder $response): Response
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
        return $response();
    }
}
