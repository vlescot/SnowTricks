<?php
declare(strict_types = 1);

namespace App\UI\Form\Handler;

use App\Domain\Entity\Comment;
use App\Domain\Entity\Trick;
use App\Domain\Repository\CommentRepository;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateCommentHandler
{
    /**
     * @var CommentRepository
     */
    private $commentRepository;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * CreateCommentHandler constructor.
     *
     * @param CommentRepository $commentRepository
     * @param ValidatorInterface $validator
     * @param SessionInterface $session
     */
    public function __construct(CommentRepository $commentRepository, ValidatorInterface $validator, SessionInterface $session)
    {
        $this->commentRepository = $commentRepository;
        $this->validator = $validator;
        $this->session = $session;
    }

    /**
     * @param Form $form
     * @param UserInterface $user
     * @param Trick $trick
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function handle(Form $form, UserInterface $user, Trick $trick) : bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $comment = new Comment(
                $form->get('content')->getData(),
                $user,
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
