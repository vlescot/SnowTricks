<?php
declare(strict_types = 1);

namespace App\UI\Action;

use App\Domain\Repository\Interfaces\TrickRepositoryInterface;
use App\UI\Action\Interfaces\TrickPageActionInterface;
use App\UI\Form\Handler\Interfaces\CreateCommentHandlerInterface;
use App\UI\Form\Type\CommentType;
use App\UI\Responder\Interfaces\TwigResponderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * @Route(
 *     "/{slug}",
 *     name="Trick",
 *     methods={"GET", "POST"}
 * )
 *
 * Class TrickPageAction
 * @package App\Action
 */
final class TrickPageAction implements TrickPageActionInterface
{
    /**
     * @var TrickRepositoryInterface
     */
    private $trickRepository;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var CreateCommentHandlerInterface
     */
    private $commentHandler;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        TrickRepositoryInterface $trickRepository,
        FormFactoryInterface $formFactory,
        CreateCommentHandlerInterface $commentHandler,
        AuthorizationCheckerInterface $authorizationChecker
    ) {
        $this->trickRepository = $trickRepository;
        $this->formFactory = $formFactory;
        $this->commentHandler = $commentHandler;
        $this->authorizationChecker = $authorizationChecker;
    }


    /**
     * @inheritdoc
     */
    public function __invoke(Request $request, TwigResponderInterface $responder): Response
    {
        if (!$trick = $this->trickRepository->findOneBy(['slug' => $request->attributes->get('slug')])) {
            throw new NotFoundHttpException();
        }

        if ($this->authorizationChecker->isGranted('ROLE_USER')) {
            $form = $this->formFactory->create(CommentType::class)
                                      ->handleRequest($request);

            $this->commentHandler->handle($form, $trick);
        }

        return $responder(
            'app/trick_page.html.twig',
            [
                'trick' => $trick,
                'form' => $form ?? null
            ]
        );
    }
}
