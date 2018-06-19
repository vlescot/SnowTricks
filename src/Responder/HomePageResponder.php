<?php

namespace App\Responder;

use Symfony\Component\HttpFoundation\Response;

class HomePageResponder
{
    /**
     * @Var Twig_Environment
     */
    private $twig;

    /**
     * HomePageResponder constructor.
     *
     * @param $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param $tricks
     *
     * @return Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke($tricks)
    {
        return new Response(
            $this->twig->render('snowtricks/index.html.twig', [
                'tricks' => $tricks,
            ])
        );
    }
}
