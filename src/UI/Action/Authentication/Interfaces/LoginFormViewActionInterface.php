<?php
declare(strict_types=1);

namespace App\UI\Action\Authentication\Interfaces;

use App\UI\Responder\Interfaces\TwigResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

interface LoginFormViewActionInterface
{
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
    );

    /**
     * @param TwigResponderInterface $responder
     *
     * @return Response
     */
    public function __invoke(TwigResponderInterface $responder): Response;
}
