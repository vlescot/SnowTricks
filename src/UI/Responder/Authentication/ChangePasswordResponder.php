<?php
declare(strict_types=1);

namespace App\UI\Responder\Authentication;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

class ChangePasswordResponder
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;


    /**
     * ChangePasswordResponder constructor.
     *
     * @param Environment $twig
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(Environment $twig, UrlGeneratorInterface $urlGenerator)
    {
        $this->twig = $twig;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param FormInterface $form
     *
     * @return string
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(bool $redirect, FormInterface $form = null)
    {
        $response = $redirect
            ?   new RedirectResponse($this->urlGenerator->generate('Home'))
            :   new Response($this->twig->render(
                    'authentication/change_password.html.twig', [
                        'form' => $form->createView()
                    ]
                ))
            ;

        return $response;
    }
}
