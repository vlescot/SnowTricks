<?php

namespace App\Responder;

use App\Domain\Entity\Trick;
use Symfony\Component\HttpFoundation\Response;

class TrickPageResponder
{
    /**
     * @Var Twig_Environment
     */
    private $twig;

    /**
     * HomePageResponder constructor.
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
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
    public function __invoke(Trick $trick)
    {
        return new Response(
            $this->twig->render('snowtricks/trick.html.twig', [
                'trick' => $trick,
            ])
        );
    }
}
