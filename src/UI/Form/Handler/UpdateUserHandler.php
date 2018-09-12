<?php
declare(strict_types=1);

namespace App\UI\Form\Handler;

use App\Domain\Builder\Interfaces\UpdateUserBuilderInterface;
use App\Domain\Repository\Interfaces\PictureRepositoryInterface;
use App\Domain\Repository\Interfaces\UserRepositoryInterface;
use App\UI\Form\Handler\Interfaces\UpdateUserHandlerInterface;
use App\Service\Image\Interfaces\ImageRemoverInterface;
use App\Service\Image\Interfaces\ImageThumbnailCreatorInterface;
use App\Service\Image\Interfaces\ImageUploaderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UpdateUserHandler implements UpdateUserHandlerInterface
{
    /**
     * @var UpdateUserBuilderInterface
     */
    private $updateUserBuilder;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var PictureRepositoryInterface
     */
    private $pictureRepository;

    /**
     * @var ImageUploaderInterface
     */
    private $imageUploader;

    /**
     * @var ImageThumbnailCreatorInterface
     */
    private $thumbnailCreator;

    /**
     * @var ImageRemoverInterface
     */
    private $imageRemover;

    /**
     * UpdateUserHandler constructor.
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
    ) {
        $this->updateUserBuilder = $updateUserBuilder;
        $this->validator = $validator;
        $this->session = $session;
        $this->userRepository = $userRepository;
        $this->pictureRepository = $pictureRepository;
        $this->imageUploader = $imageUploader;
        $this->thumbnailCreator = $thumbnailCreator;
        $this->imageRemover = $imageRemover;
    }


    /**
     * @param FormInterface $form
     * @param UserInterface $user
     *
     * @return bool
     */
    public function handle(FormInterface $form, UserInterface $user): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $oldPicture = $user->getPicture();

            $user = $this->updateUserBuilder->create($user, $form->getData());

            $errors = $this->validator->validate($user, null, ['userRegistration', 'User']);
            if (\count($errors) > 0) {
                foreach ($errors as $violation) {
                    $this->session->getFlashBag()->add('warning', $violation->getMessage());
                }
                return false;
            }

            $this->userRepository->save($user);

            if ($oldPicture !== $user->getPicture()) {
                $this->pictureRepository->remove($oldPicture);
            }

            $this->imageUploader->uploadFiles();
            $this->thumbnailCreator->createThumbnails();

            $this->session->getFlashBag()->add('success', 'Ton profil a bien été mise à jour');

            return true;
        }
        return false;
    }
}
