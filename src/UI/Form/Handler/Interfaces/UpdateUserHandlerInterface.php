<?php
declare(strict_types=1);

namespace App\UI\Form\Handler\Interfaces;

use App\Domain\Builder\Interfaces\UpdateUserBuilderInterface;
use App\Domain\Repository\Interfaces\PictureRepositoryInterface;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\Service\Image\Interfaces\ImageRemoverInterface;
use App\Service\Image\Interfaces\ImageThumbnailCreatorInterface;
use App\Service\Image\Interfaces\ImageUploaderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

interface UpdateUserHandlerInterface
{

    /**
     * UpdateUserHandlerInterface constructor.
     *
     * @param UpdateUserBuilderInterface $updateUserBuilder
     * @param ValidatorInterface $validator
     * @param SessionInterface $session
     * @param UserRepositoryInterface $userRepository
     * @param PictureRepositoryInterface $pictureRepository
     * @param ImageUploaderInterface $imageUploader
     * @param ImageThumbnailCreatorInterface $thumbnailCreator
     * @param ImageRemoverInterface $imageRemover
     */
    public function __construct(
        UpdateUserBuilderInterface $updateUserBuilder,
        ValidatorInterface $validator,
        SessionInterface $session,
        UserRepositoryInterface $userRepository,
        PictureRepositoryInterface $pictureRepository,
        ImageUploaderInterface $imageUploader,
        ImageThumbnailCreatorInterface $thumbnailCreator,
        ImageRemoverInterface $imageRemover
    );

    /**
     * @param FormInterface $form
     * @param UserInterface $user
     *
     * @return bool
     */
    public function handle(FormInterface $form, UserInterface $user): bool;
}
