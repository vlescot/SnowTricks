<?php
declare(strict_types=1);

namespace App\UI\Action\Authentication;

use App\UI\Action\Authentication\Interfaces\LoginFormViewActionInterface;
use App\UI\Form\Type\Authentication\LoginType;
use App\UI\Responder\Interfaces\TwigResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route(
 *     "/connexion",
 *     name="LoginForm",
 *     methods={"GET"}
 * )
 *
 * Class LoginFormViewAction
 * @package App\UI\Action\Authentication
 */
final class LoginFormViewAction implements LoginFormViewActionInterface
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
     * LoginFormViewAction constructor.
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
     * @param TwigResponderInterface $responder
     *
     * @return Response
     */
    public function __invoke(TwigResponderInterface $responder): Response
    {
        $form = $this->formFactory->create(LoginType::class, null, [
            'action' => $this->urlGenerator->generate('Login')
        ]);

        $lastUsername = $this->authenticationUtils->getLastUsername();

        return $responder(
            'security/login.html.twig',
            [
                'form' => $form,
                'last_username' => $lastUsername,
                'modal' => null
            ]
        );
    }
}
