<?php
declare(strict_types=1);

namespace App\UI\Form\Handler\Interfaces;

use App\App\Interfaces\MailerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

interface ResetPasswordHandlerInterface
{
    /**
     * ResetPasswordHandlerInterface constructor.
     *
     * @param UserProviderInterface $userProvider
     * @param SessionInterface $session
     * @param MailerInterface $mailer
     */
    public function __construct(
        UserProviderInterface $userProvider,
        SessionInterface $session,
        MailerInterface $mailer
    );

    /**
     * @param FormInterface $form
     *
     * @return bool
     */
    public function handle(FormInterface $form): bool;
}
