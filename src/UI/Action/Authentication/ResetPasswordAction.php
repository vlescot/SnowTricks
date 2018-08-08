<?php
declare(strict_types=1);

namespace App\UI\Action\Authentication;

use App\UI\Form\Handler\ResetPasswordHandler;
use App\UI\Form\Type\Authentication\ResetPasswordType;
use App\UI\Responder\Authentication\ModalResponder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/reset_password", name="ResetPassword")
 * @Method({"GET", "POST"})
 *
 * Class ResetPasswordAction
 * @package App\UI\Action\Authentication
 */
class ResetPasswordAction
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var ResetPasswordHandler
     */
    private $resetPasswordHandler;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var AuthenticationUtils
     */
    private $authenticationUtils;

    /**
     * ResetPasswordAction constructor.
     *
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        ResetPasswordHandler $resetPasswordHandler,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->formFactory = $formFactory;
        $this->resetPasswordHandler = $resetPasswordHandler;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param AuthenticationUtils $authenticationUtils
     * @param Request $request
     * @param ModalResponder $responder
     *
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     *
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(
        AuthenticationUtils $authenticationUtils,
        Request $request,
        ModalResponder $responder
    ) {
        $form = $this->formFactory->create(ResetPasswordType::class)
                                  ->handleRequest($request);

        $this->resetPasswordHandler->handle($form);

        return new RedirectResponse($request->headers->get('referer'));
    }
}
