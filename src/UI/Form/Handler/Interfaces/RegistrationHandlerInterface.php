<?php
declare(strict_types=1);

namespace App\UI\Form\Handler\Interfaces;

use App\Domain\Builder\Interfaces\UserBuilderInterface;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\App\Image\Interfaces\ImageThumbnailCreatorInterface;
use App\App\Image\Interfaces\ImageUploaderInterface;
use App\App\Interfaces\MailerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

interface RegistrationHandlerInterface
{
    /**
     * RegistrationHandlerInterface constructor.
     *
     * @param ValidatorInterface $validator
     * @param UserRepositoryInterface $userRepository
     * @param UserBuilderInterface $userBuilder
     * @param SessionInterface $session
     * @param ImageUploaderInterface $imageUploader
     * @param ImageThumbnailCreatorInterface $thumbnailCreator
     * @param MailerInterface $mailer
     */
    public function __construct(
        ValidatorInterface $validator,
        UserRepositoryInterface $userRepository,
        UserBuilderInterface $userBuilder,
        SessionInterface $session,
        ImageUploaderInterface $imageUploader,
        ImageThumbnailCreatorInterface $thumbnailCreator,
        MailerInterface $mailer
    );

    /**
     * @param FormInterface $form
     *
     * @return bool
     */
    public function handle(FormInterface $form) :bool;
}
