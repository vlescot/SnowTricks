<?php
declare(strict_types = 1);

namespace App\UI\Responder;

use App\UI\Responder\Interfaces\EditTrickResponderInterface;
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
// TODO
// TODO RENAME CrUp par Edit
// TODO
final class EditTrickResponder implements EditTrickResponderInterface
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
     * {@inheritdoc}
     */
    public function __construct(
        Environment $twig,
        SessionInterface $session,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->twig = $twig;
        $this->session = $session;
        $this->urlGenerator = $urlGenerator;
    }


    /**
     * {@inheritdoc}
     */
    public function __invoke(string $view, bool $redirect = true, FormInterface $form = null, string $slug = null)
    {
        $response = $redirect
            ?   new RedirectResponse(
                $this->urlGenerator->generate('Trick', ['slug' => $this->session->get('slug')])
            )
            :   new Response(
                $this->twig->render('app/CRUD/'. $view .'_trick.html.twig', [
                    'form' => $form->createView(),
                    'slug' => $slug
                ])
            )
        ;

        return $response;
    }
}
