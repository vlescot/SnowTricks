<?php
declare(strict_types=1);

namespace App\Service\Interfaces;

interface MailerInterface
{
    /**
     * MailerInterface constructor.
     *
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $twig
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig);

    /**
     * @param string $mailTo
     * @param string $subject
     * @param string $view
     * @param array $parameters
     *
     * @return mixed
     */
    public function sendMail(string $mailTo, string $subject, string $view, array $parameters);
}
