<?php
declare(strict_types = 1);

namespace App\UI\Form\Handler;

use App\Domain\Entity\Comment;
use App\Domain\Entity\Interfaces\TrickInterface;
use App\Domain\Entity\User;
use App\Domain\Repository\Interfaces\CommentRepositoryInterface;
use App\UI\Form\Handler\Interfaces\CreateCommentHandlerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CreateCommentHandler implements CreateCommentHandlerInterface
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var User
     */
    private $user;

    /**
     * @var CommentRepositoryInterface
     */
    private $commentRepository;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        ValidatorInterface $validator,
        SessionInterface $session,
        TokenStorageInterface $tokenStorage,
        CommentRepositoryInterface $commentRepository
    ) {
        $this->validator = $validator;
        $this->session = $session;
        $this->user = $tokenStorage->getToken()->getUser();
        $this->commentRepository = $commentRepository;
    }


    /**
     * {@inheritdoc}
     */
    public function handle(FormInterface $form, TrickInterface $trick) : bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $comment = new Comment(
                $form->getData()->content,
                $this->user,
                $trick
            );

            $errors = $this->validator->validate($comment, null, 'comment');

            if (\count($errors) > 0) {
                foreach ($errors as $violation) {
                    $this->session->getFlashBag()->add('warning', $violation->getMessage());
                }
                return false;
            }

            $this->commentRepository->save($comment);

            $this->session->getFlashBag()->add('success', 'Votre commentaire a bien été ajouté.');

            return true;
        }
        return false;
    }
}
