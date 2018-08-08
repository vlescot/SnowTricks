<?php
declare(strict_types = 1);

namespace App\UI\Responder;

use App\Domain\Entity\Trick;
use Symfony\Component\Form\FormInterface;
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
     * @param Trick $trick
     * @param FormInterface $form
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(Trick $trick, FormInterface $form)
    {
        return new Response(
            $this->twig->render('snowtricks/trick_page.html.twig', [
                'trick' => $trick,
                'form' => $form->createView()
            ])
        );
    }
}
