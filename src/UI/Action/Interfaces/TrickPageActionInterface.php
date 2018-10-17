<?php
declare(strict_types=1);

namespace App\UI\Action\Interfaces;

use App\Domain\Repository\Interfaces\TrickRepositoryInterface;
use App\UI\Form\Handler\Interfaces\CreateCommentHandlerInterface;
use App\UI\Responder\Interfaces\TwigResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

interface TrickPageActionInterface
{
    /**
     * TrickPageActionInterface constructor.
     *
     * @param TrickRepositoryInterface $trickRepository
     * @param FormFactoryInterface $formFactory
     * @param CreateCommentHandlerInterface $commentHandler
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function __construct(
        TrickRepositoryInterface $trickRepository,
        FormFactoryInterface $formFactory,
        CreateCommentHandlerInterface $commentHandler,
        AuthorizationCheckerInterface $authorizationChecker
    );

    /**
     * @param Request $request
     * @param TwigResponderInterface $responder
     *
     * @return Response
     */
    public function __invoke(Request $request, TwigResponderInterface $responder): Response;
}
