<?php
declare(strict_types=1);

namespace App\UI\Action\Authentication;

use App\UI\Form\Type\Authentication\LoginType;
use App\UI\Responder\Authentication\LoginFormViewResponder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/connexion", name="LoginForm")
 * @Method("GET")
 *
 * Class LoginFormViewAction
 * @package App\UI\Action\Authentication
 */
class LoginFormViewAction
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
     * @param LoginFormViewResponder $responder
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(LoginFormViewResponder $responder)
    {
        $form = $this->formFactory->create(LoginType::class, null, [
            'action' => $this->urlGenerator->generate('Login')
        ]);

        $lastUsername = $this->authenticationUtils->getLastUsername();

        return $responder($form, $lastUsername);
    }
}
