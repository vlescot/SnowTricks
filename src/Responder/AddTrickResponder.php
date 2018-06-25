<?php

namespace App\Responder;

use App\Domain\Entity\Trick;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class AddTrickResponder
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * AddTrickResponder constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param Form $form
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(Form $form)
    {
        return new Response(
            $this->twig->render('CRUD/addTrick.html.twig', [
                'form' => $form->createView(),
            ])
        );
    }
}