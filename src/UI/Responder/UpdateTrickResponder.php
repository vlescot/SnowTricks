<?php
declare(strict_types = 1);

namespace App\UI\Responder;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

/**
 * Class UpdateTrickResponder
 * @package App\Responder
 */
class UpdateTrickResponder
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * UpdateTrickResponder constructor.
     *
     * @param Environment $twig
     * @param SessionInterface $session
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(Environment $twig, SessionInterface $session, UrlGeneratorInterface $urlGenerator)
    {
        $this->twig = $twig;
        $this->session = $session;
        $this->urlGenerator = $urlGenerator;
    }


    /**
     * @param bool $redirect
     * @param FormInterface|null $form
     *
     * @return RedirectResponse|Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(bool $redirect = true, FormInterface $form = null)
    {
        $response = $redirect
            ?   new RedirectResponse(
                $this->urlGenerator->generate('Trick', ['slug' => $this->session->get('slug')])
            )
            :   new Response(
                $this->twig->render('snowtricks/CRUD/update_trick.html.twig', ['form' => $form->createView()] )
            )
        ;

        return $response;
    }
}
