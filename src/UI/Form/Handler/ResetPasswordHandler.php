<?php
declare(strict_types=1);

namespace App\UI\Form\Handler;

use App\Domain\Repository\UserRepository;
use App\Service\Mailer;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class ResetPasswordHandler
{
    /**
     * @var UserProviderInterface
     */
    private $userProvider;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var UserRepository
     */
    private $userRepository;


    /**
     * ResetPasswordHandler constructor.
     *
     * @param UserProviderInterface $userProvider
     * @param SessionInterface $session
     * @param Mailer $mailer
     * @param UserRepository $userRepository
     */
    public function __construct(
        UserProviderInterface $userProvider,
        SessionInterface $session,
        Mailer $mailer,
        UserRepository $userRepository
    ) {
        $this->userProvider = $userProvider;
        $this->session = $session;
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
    }

    /**
     * @param FormInterface $form
     *
     * @return bool
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function handle(FormInterface $form)
    {
        if ($form->isSubmitted() && $form->isValid()) {

            $login = $form->get('login')->getData();

            if ($user = $this->userProvider->loadUserByUsername($login)) {
                $user->enabled(false);
                $this->userRepository->save($user);

                $this->mailer->sendMail(
                    $user->getEmail(),
                    'SnowTrick - Mot de pas oublié',
                    'email_member_reset_password.html.twig', [
                        'username' => $user->getUsername(),
                        'token' => $user->getToken()
                    ]
                );

                $this->session->getFlashBag()->add('success', 'Un e-mail vient de vous être envoyer');

                return true;
            }
            $this->session->getFlashBag()->add('warning', 'Nous n\'avons pas reconnu votre identifiant');
        }
        return false;
    }
}