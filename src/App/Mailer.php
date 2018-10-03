<?php
declare(strict_types=1);

namespace App\App;

use App\App\Interfaces\MailerInterface;

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
     * {@inheritdoc}
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    /**
     * {@inheritdoc}
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
