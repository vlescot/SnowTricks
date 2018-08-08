<?php
declare(strict_types=1);

namespace App\UI\Responder;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class RemoveTrickResponder
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * LoginResponder constructor.
     *
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param bool $redirect
     * @param array|null $data
     *
     * @return RedirectResponse
     */
    public function __invoke(bool $redirect = false, array $data = null)
    {
        $response = $redirect
            ?   new RedirectResponse($data['content'], $data['status'])
            :   new Response($this->twig->render('authentication/modal2.html.twig', [
                    'slug' => $data['slug'],
                    'title' => $data['title']
                ])
            )
        ;

        return $response;
    }
}