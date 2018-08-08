<?php
declare(strict_types = 1);

namespace App\UI\Action;

use App\Domain\Entity\User;
use App\Domain\Repository\TrickRepository;
use App\UI\Form\Handler\CreateCommentHandler;
use App\UI\Form\Type\CommentType;
use App\UI\Responder\TrickPageResponder;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/{slug}", name="Trick")
 * @Method({"GET", "POST"})
 *
 * Class TrickPageAction
 * @package App\Action
 */
class TrickPageAction
{
    /**
     * @var TrickRepository
     */
    private $trickRepository;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var CreateCommentHandler
     */
    private $commentHandler;

    /**
     * @var User
     */
    private $user;

    /**
     * TrickPageAction constructor.
     *
     * @param TrickRepository $trickRepository
     * @param FormFactoryInterface $formFactory
     * @param CreateCommentHandler $commentHandler
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        TrickRepository $trickRepository,
        FormFactoryInterface $formFactory,
        CreateCommentHandler $commentHandler,
        TokenStorageInterface $tokenStorage
    ) {
        $this->trickRepository = $trickRepository;
        $this->formFactory = $formFactory;
        $this->commentHandler = $commentHandler;
        $this->user = $tokenStorage->getToken();
    }

    /**
     * @param Request $request
     * @param TrickPageResponder $responder
     *
     * @return Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function __invoke(Request $request,TrickPageResponder $responder): Response
    {
        $trick = $this->trickRepository->findOneBy(['slug' => $request->attributes->get('slug') ]);

        $form = $this->formFactory->create(CommentType::class)
                                  ->handleRequest($request);

        if ($this->user instanceof UserInterface) {
            $this->commentHandler->handle($form, $this->user, $trick);
        }

        return $responder($trick, $form);
    }
}
