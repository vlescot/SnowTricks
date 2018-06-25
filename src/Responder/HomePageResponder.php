<?php

namespace App\Responder;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class HomePageResponder
{
    /**
     * @Var Environment
     */
    private $twig;

    /**
     * HomePageResponder constructor.
     * @param \Twig_Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param $tricks
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke($tricks)
    {
        return new Response(
            $this->twig->render('snowtricks/homepage.html.twig', [
                'tricks' => $tricks,
            ])
        );
    }
}
