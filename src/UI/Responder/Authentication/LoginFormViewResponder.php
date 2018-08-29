<?php
declare(strict_types=1);

namespace App\UI\Responder\Authentication;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class LoginFormViewResponder
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * LoginFormViewResponder constructor.
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @param FormInterface $form
     * @param string $lastUsername
     *
     * @return Response
     *
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(FormInterface $form, string $lastUsername)
    {
        return new Response($this->twig->render('authentication/login.html.twig', [
            'form' => $form->createView(),
            'modal' => null,
            'last_username' => $lastUsername,
        ]));
    }
}
