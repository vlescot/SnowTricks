<?php
declare(strict_types=1);

namespace App\UI\Action\Interfaces;

use App\Domain\Repository\Interfaces\TrickRepositoryInterface;
use App\UI\Responder\Interfaces\TwigResponderInterface;
use Symfony\Component\HttpFoundation\Response;

interface HomeActionInterface
{
    /**
     * HomeActionInterface constructor.
     *
     * @param TrickRepositoryInterface $TrickRepository
     */
    public function __construct(TrickRepositoryInterface $TrickRepository);

    /**
     * @param TwigResponderInterface $responder
     *
     * @return Response
     */
    public function __invoke(TwigResponderInterface $responder): Response;
}
