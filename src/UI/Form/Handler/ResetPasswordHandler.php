<?php
declare(strict_types=1);

namespace App\UI\Form\Handler;

use App\UI\Form\Handler\Interfaces\ResetPasswordHandlerInterface;
use App\Service\Interfaces\MailerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class ResetPasswordHandler implements ResetPasswordHandlerInterface
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
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @inheritdoc
     */
    public function __construct(
        UserProviderInterface $userProvider,
        SessionInterface $session,
        MailerInterface $mailer
    ) {
        $this->userProvider = $userProvider;
        $this->session = $session;
        $this->mailer = $mailer;
    }

    /**
     * @inheritdoc
     */
    public function handle(FormInterface $form): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $username = $form->get('username')->getData();

            if ($user = $this->userProvider->loadUserByUsername($username)) {
                $this->mailer->sendMail(
                    $user->getEmail(),
                    'SnowTrick - Mot de pas oublié',
                    'email_member_reset_password.html.twig',
                    [
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
