<?php
declare(strict_types=1);

namespace App\UI\Action\Interfaces;

use App\Domain\Factory\Interfaces\TrickDTOFactoryInterface;
use App\Domain\Repository\Interfaces\TrickRepositoryInterface;
use App\UI\Form\Handler\Interfaces\UpdateTrickHandlerInterface;
use App\UI\Responder\Interfaces\CrUpTrickResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

interface UpdateTrickActionInterface
{
    /**
     * UpdateTrickActionInterface constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param TrickRepositoryInterface $trickRepository
     * @param UpdateTrickHandlerInterface $updateTrickHandler
     * @param TrickDTOFactoryInterface $trickDTOFactory
     * @param SessionInterface $session
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        TrickRepositoryInterface $trickRepository,
        UpdateTrickHandlerInterface $updateTrickHandler,
        TrickDTOFactoryInterface $trickDTOFactory,
        SessionInterface $session
    );

    /**
     * @param Request $request
     * @param CrUpTrickResponderInterface $responder
     *
     * @return Response
     */
    public function __invoke(Request $request, CrUpTrickResponderInterface $responder): Response;
}
