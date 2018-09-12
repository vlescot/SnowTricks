<?php
declare(strict_types=1);

namespace App\UI\Action\Authentication;

use App\UI\Action\Authentication\Interfaces\AuthenticationViewActionInterface;
use App\UI\Form\Type\Authentication\ChangePasswordType;
use App\UI\Form\Type\Authentication\LoginType;
use App\UI\Form\Type\Authentication\RegistrationType;
use App\UI\Form\Type\Authentication\ResetPasswordType;
use App\UI\Form\Type\Authentication\UpdateUserType;
use App\UI\Responder\Authentication\Interfaces\ModalResponderInterface;
use App\UI\Responder\Interfaces\TwigResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route(
 *     "/authentication/{modal}",
 *     name="ModalView",
 *     methods={"GET"}
 * )
 *
 * Class ModalViewAction
 * @package App\UI\Action\Authentication
 *
 *
 *      This class manage to display the proper modal view
 */
final class AuthenticationViewAction implements AuthenticationViewActionInterface
{

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var AuthenticationUtils
     */
    private $authenticationUtils;

    /**
     * AuthenticationViewAction constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param UrlGeneratorInterface $urlGenerator
     * @param AuthenticationUtils $authenticationUtils
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        UrlGeneratorInterface $urlGenerator,
        AuthenticationUtils $authenticationUtils
    ) {
        $this->formFactory = $formFactory;
        $this->urlGenerator = $urlGenerator;
        $this->authenticationUtils = $authenticationUtils;
    }

    /**
     * @param Request $request
     * @param TwigResponderInterface $responder
     *
     * @return Response
     */
    public function __invoke(Request $request, TwigResponderInterface $responder): Response
    {
        $modal = $request->attributes->get('modal');

        switch ($modal) {
            case 'login':
                $form = $this->formFactory->create(LoginType::class, null, [
                    'action' => $this->urlGenerator->generate('Login')
                ]);
                break;

            case 'registration':
                $form = $this->formFactory->create(RegistrationType::class, null, [
                    'action' => $this->urlGenerator->generate('Registration')
                ]);
                break;

            case 'change_password':
                $form = $this->formFactory->create(ChangePasswordType::class, null, [
                    'action' => $this->urlGenerator->generate('ChangePassword')
                ]);
                break;

            case 'reset_password':
                $form = $this->formFactory->create(ResetPasswordType::class, null, [
                    'action' => $this->urlGenerator->generate('ResetPassword')
                ]);
                break;

            case 'update_user':
                $form = $this->formFactory->create(UpdateUserType::class, null, [
                    'action' => $this->urlGenerator->generate('UpdateUser')
                ]);
                break;
            default:
                throw new NotFoundHttpException('Cette page n\'existe pas...');
        }

        $lastUsername = $this->authenticationUtils->getLastUsername();

        return $responder(
            'authentication/modal_'. $modal .'.html.twig', [
                'form' => $form,
                'last_username' => $lastUsername,
                'modal' => $modal
            ]
        );
    }
}
