<?php
declare(strict_types=1);

namespace App\Service;

use App\Service\Interfaces\MailerInterface;

final class Mailer implements MailerInterface
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * Mailer constructor.
     *
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $twig
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * @param string $subject
     * @param string $mailTo
     * @param string $view
     * @param array $parameters
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendMail(
        string $mailTo,
        string $subject,
        string $view,
        array $parameters
    ) {
        $message = (new \Swift_Message($subject))
            ->setFrom(['vincent.lescot@gmail.com' => 'La Communauté SnowTricks'])
            ->setTo($mailTo)
            ->setBody(
                $this->twig->render(
                    'email/' . $view,
                    $parameters
                ),
                'text/html'
            );

        $this->mailer->send($message);
    }
}
