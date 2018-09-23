<?php
declare(strict_types=1);

namespace App\UI\Form\Handler\Interfaces;

use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

interface ChangePasswordHandlerInterface
{
    /**
     * ChangePasswordHandlerInterface constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserRepositoryInterface $userRepository
     * @param ValidatorInterface $validator
     * @param SessionInterface $session
     */
    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        UserRepositoryInterface $userRepository,
        ValidatorInterface $validator,
        SessionInterface $session
    );

    /**
     * @param FormInterface $form
     * @param null|UserInterface $user
     *
     * @return bool
     */
    public function handle(FormInterface $form, ? UserInterface $user): bool;
}
