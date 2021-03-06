<?php
declare(strict_types=1);

namespace App\UI\Action\Interfaces;

use App\UI\Form\Handler\Interfaces\CreateTrickHandlerInterface;
use App\UI\Responder\Interfaces\EditTrickResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface CreateTrickActionInterface
{
    /**
     * CreateTrickActionInterface constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param CreateTrickHandlerInterface $addTrickHandler
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        CreateTrickHandlerInterface $addTrickHandler
    );

    /**
     * @param Request $request
     * @param EditTrickResponderInterface $responder
     *
     * @return Response
     */
    public function __invoke(Request $request, EditTrickResponderInterface $responder) :Response;
}
