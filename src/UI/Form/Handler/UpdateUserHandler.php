<?php
declare(strict_types=1);

namespace App\UI\Form\Handler;

use App\Domain\Builder\UpdateUserBuilder;
use App\Domain\CollectionManager\CollectionChecker\PictureCollectionChecker;
use App\Domain\Entity\User;
use App\Domain\Repository\PictureRepository;
use App\Domain\Repository\UserRepository;
use App\UI\Service\Image\ImageThumbnailCreator;
use App\UI\Service\Image\ImageUploader;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UpdateUserHandler
{
    /**
     * @var UpdateUserBuilder
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
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var PictureRepository
     */
    private $pictureRepository;

    /**
     * @var ImageUploader
     */
    private $imageUploader;

    /**
     * @var ImageThumbnailCreator
     */
    private $thumbnailCreator;

    /**
     * @var PictureCollectionChecker
     */
    private $pictureChecker;

    /**
     * UpdateUserHandler constructor.
     *
     * @param UpdateUserBuilder $updateUserBuilder
     * @param ValidatorInterface $validator
     * @param SessionInterface $session
     * @param UserRepository $userRepository
     * @param PictureRepository $pictureRepository
     * @param ImageUploader $imageUploader
     * @param ImageThumbnailCreator $thumbnailCreator
     * @param PictureCollectionChecker $pictureChecker
     */
    public function __construct(
        UpdateUserBuilder $updateUserBuilder,
        ValidatorInterface $validator,
        SessionInterface $session,
        UserRepository $userRepository,
        PictureRepository $pictureRepository,
        ImageUploader $imageUploader,
        ImageThumbnailCreator $thumbnailCreator,
        PictureCollectionChecker $pictureChecker
    ) {
        $this->updateUserBuilder = $updateUserBuilder;
        $this->validator = $validator;
        $this->session = $session;
        $this->userRepository = $userRepository;
        $this->pictureRepository = $pictureRepository;
        $this->imageUploader = $imageUploader;
        $this->thumbnailCreator = $thumbnailCreator;
        $this->pictureChecker = $pictureChecker;
    }


    /**
     * @param FormInterface $form
     * @param User $user
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function handle(FormInterface $form, User $user)
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->updateUserBuilder->create($user, $form->getData());

            $errors = $this->validator->validate($user, null, ['userRegistration', 'User']);
            if (\count($errors) > 0) {
                foreach ($errors as $violation) {
                    $this->session->getFlashBag()->add('warning', $violation->getMessage());
                }
                return false;
            }

            $this->userRepository->save($user);

            $this->pictureRepository->remove($this->pictureChecker->getDeletedObject()[0]);
            $this->imageUploader->uploadFiles();
            $this->thumbnailCreator->createThumbnails();

            $this->session->getFlashBag()->add('success', 'Ton profil a bien été mise à jour');

            return true;
        }
        return false;
    }
}
