<?php
declare(strict_types = 1);

namespace App\UI\Form\Handler;

use App\Domain\Entity\User;
use App\Domain\Repository\UserRepository;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class ChangePasswordHandler
 * @package App\UI\Form\Handler
 */
class ChangePasswordHandler
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * ChangePasswordHandler constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserRepository $userRepository
     * @param ValidatorInterface $validator
     * @param SessionInterface $session
     */
    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        UserRepository $userRepository,
        ValidatorInterface $validator,
        SessionInterface $session
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
        $this->session = $session;
    }


    /**
     * @param Form $form
     * @param UserInterface $user
     *
     * @return bool
     */
    public function handle(Form $form, UserInterface $user)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->passwordEncoder->encodePassword($user, $form->get('password')->getData());
            $user->changePassword($password);

            $errors = $this->validator->validate($user, null, ['userRegistration', 'User']);
            if (\count($errors) > 0) {
                foreach ($errors as $violation) {
                    $this->session->getFlashBag()->add('warning', $violation->getMessage());
                }
                return false;
            }

            $this->userRepository->save($user);

            $this->session->getFlashBag()->add('success', 'Votre mot de passe à bien été changé');

            return true;
        }
        return false;
    }
}
