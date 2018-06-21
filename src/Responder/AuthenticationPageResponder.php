<?php

namespace App\Responder;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class AuthenticationPageResponder
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * AuthenticationPageResponder constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param string $modal
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(string $modal)
    {
        return new Response(
            $this->twig->render('auth/'.$modal.'.html.twig', [
                'id' => $modal
            ])
        );
    }
}
