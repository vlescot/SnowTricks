<?php
declare(strict_types = 1);

namespace App\UI\Form\Handler;

use App\Domain\Entity\Comment;
use App\Domain\Entity\Trick;
use App\Domain\Entity\User;
use App\Domain\Repository\CommentRepository;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateCommentHandler
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
     * @var CommentRepository
     */
    private $commentRepository;

    /**
     * CreateCommentHandler constructor.
     *
     * @param CommentRepository $commentRepository
     * @param ValidatorInterface $validator
     * @param SessionInterface $session
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        ValidatorInterface $validator,
        SessionInterface $session,
        TokenStorageInterface $tokenStorage,
        CommentRepository $commentRepository
    ) {
        $this->validator = $validator;
        $this->session = $session;
        $this->commentRepository = $commentRepository;
        $this->user = $tokenStorage->getToken()->getUser();
    }


    /**
     * @param Form $form
     * @param Trick $trick
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function handle(Form $form, Trick $trick) : bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $comment = new Comment(
                $form->get('content')->getData(),
                $this->user,
                $trick
            );

            $errors = $this->validator->validate($comment, null, 'create_comment');

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
