<?php
declare(strict_types=1);

namespace App\UI\Responder\Interfaces;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

interface EditTrickResponderInterface
{
    /**
     * UpdateTrickResponderInterface constructor.
     *
     * @param Environment $twig
     * @param SessionInterface $session
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(
        Environment $twig,
        SessionInterface $session,
        UrlGeneratorInterface $urlGenerator
    );

    /**
     * @param string $view
     * @param bool $redirect
     * @param FormInterface|null $form
     * @param string|null $slug
     *
     * @return mixed
     */
    public function __invoke(string $view, bool $redirect = true, FormInterface $form = null, string $slug = null);
}
