<?php
declare(strict_types=1);

namespace App\UI\Form\Handler\Interfaces;

use App\Domain\Entity\Interfaces\TrickInterface;
use App\Domain\Repository\Interfaces\CommentRepositoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

interface CreateCommentHandlerInterface
{
    /**
     * CreateCommentHandlerInterface constructor.
     *
     * @param ValidatorInterface $validator
     * @param SessionInterface $session
     * @param TokenStorageInterface $tokenStorage
     * @param CommentRepositoryInterface $commentRepository
     */
    public function __construct(
        ValidatorInterface $validator,
        SessionInterface $session,
        TokenStorageInterface $tokenStorage,
        CommentRepositoryInterface $commentRepository
    );

    /**
     * @param FormInterface $form
     * @param TrickInterface $trick
     *
     * @return bool
     */
    public function handle(FormInterface $form, TrickInterface $trick) : bool;
}
