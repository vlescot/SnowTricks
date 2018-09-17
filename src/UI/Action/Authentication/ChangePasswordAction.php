<?php
declare(strict_types = 1);

namespace App\UI\Action\Authentication;

use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\UI\Action\Authentication\Interfaces\ChangePasswordActionInterface;
use App\UI\Form\Handler\Interfaces\ChangePasswordHandlerInterface;
use App\UI\Form\Type\Authentication\ChangePasswordType;
use App\UI\Responder\Authentication\Interfaces\ChangePasswordResponderInterface;
use App\UI\Responder\Interfaces\TwigResponderInterface;
use App\UI\Security\LoginFormAuthenticator;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

/**
 * @Route(
 *     "/change_password/{token}",
 *     name="ChangePassword",
 *     methods={"GET", "POST"}
 * )
 *
 * Class ChangePasswordAction
 * @package App\UI\Action\Authentication
 */
final class ChangePasswordAction implements ChangePasswordActionInterface
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

    /**
     * ChangePasswordAction constructor.
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
    ) {
        $this->formFactory = $formFactory;
        $this->changePasswordHandler = $changePasswordHandler;
        $this->session = $session;
        $this->userRepository = $userRepository;
        $this->authenticationHandler = $authenticationHandler;
        $this->loginAuthenticator = $loginAuthenticator;
    }


    /**
     * @param Request $request
     * @param TwigResponderInterface $responder
     *
     * @return Response
     */
    public function __invoke(Request $request, TwigResponderInterface $responder): Response
    {
        $token = $request->attributes->get('token');
        $user = $this->userRepository->loadUserByToken($token);

        if (null === $user) {
            $this->session->getBagFlash()->add('danger', 'Nous n\'avons pas pu vous identifier');
        }

        $form = $this->formFactory->create(ChangePasswordType::class)
                                  ->handleRequest($request);

        if ($this->changePasswordHandler->handle($form, $user)) {
            return $this->authenticationHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $this->loginAuthenticator,
                'main'
            );
        }

        return $responder(
            'security/change_password.html.twig',
            [
            'form' => $form
        ]
        );
    }
}
