<?php
declare(strict_types=1);

namespace App\UI\Responder\Interfaces;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

interface TwigResponderInterface
{
    /**
     * TwigResponderInterface constructor.
     *
     * @param Environment $twig
     */
    public function __construct(Environment $twig);

    /**
     * @param string $view
     * @param array $data
     *
     * @return Response
     */
    public function __invoke(string $view, array $data): Response;
}