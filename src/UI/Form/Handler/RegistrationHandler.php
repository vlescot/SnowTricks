<?php
declare(strict_types = 1);

namespace App\UI\Form\Handler;

use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\Service\Interfaces\MailerInterface;
use App\Domain\Builder\Interfaces\UserBuilderInterface;
use App\UI\Form\Handler\Interfaces\RegistrationHandlerInterface;
use App\Service\Image\Interfaces\ImageThumbnailCreatorInterface;
use App\Service\Image\Interfaces\ImageUploaderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class RegistrationHandler implements RegistrationHandlerInterface
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var UserBuilderInterface
     */
    private $userBuilder;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var ImageUploaderInterface
     */
    private $imageUploader;

    /**
     * @var ImageThumbnailCreatorInterface
     */
    private $thumbnailCreator;

    /**
     * @var MailerInterface
     */
    private $mailer;

    /**
     * @inheritdoc
     */
    public function __construct(
        ValidatorInterface $validator,
        UserRepositoryInterface $userRepository,
        UserBuilderInterface $userBuilder,
        SessionInterface $session,
        ImageUploaderInterface $imageUploader,
        ImageThumbnailCreatorInterface $thumbnailCreator,
        MailerInterface $mailer
    ) {
        $this->validator = $validator;
        $this->userRepository = $userRepository;
        $this->userBuilder = $userBuilder;
        $this->session = $session;
        $this->imageUploader = $imageUploader;
        $this->thumbnailCreator = $thumbnailCreator;
        $this->mailer = $mailer;
    }


    /**
     * @inheritdoc
     */
    public function handle(FormInterface $form): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->userBuilder->create($form->getData());

            $errors = $this->validator->validate($user, null, ['userRegistration', 'User']);

            if (\count($errors) > 0) {
                foreach ($errors as $violation) {
                    $this->session->getFlashBag()->add('warning', $violation->getMessage());
                }
                return false;
            }

            $this->userRepository->save($user);

            $this->imageUploader->uploadFiles();
            $this->thumbnailCreator->createThumbnails();

            $this->mailer->sendMail(
                $user->getEmail(),
                'SnowTricks - Bienvenue sur notre site',
                'email_member_registration_notification.html.twig',
                [
                    'username' => $user->getUsername(),
                    'token' => $user->getToken()
                ]
            );

            $this->session->getFlashBag()->add('success', 'Bienvenue parmis nous, ' . $form->get('username')->getData() . '. Un e-mail vient de t\'être envoyé pour confirmer ton compte.');

            return true;
        }
        return false;
    }
}
