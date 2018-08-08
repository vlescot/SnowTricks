<?php
declare(strict_types = 1);

namespace App\UI\Responder\Authentication;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class ModalResponder
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * LoginResponder constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param string $modal
     * @param FormInterface $form
     * @param null $lastUsername
     *
     * @return Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(string $modal, FormInterface $form, $lastUsername = null)
    {
        return new Response(
            $this->twig->render('authentication/'. $modal .'.html.twig', [
                'form' => $form->createView(),
                'modal' => $modal,
                'last_username' => $lastUsername
            ])
        );
    }
}
