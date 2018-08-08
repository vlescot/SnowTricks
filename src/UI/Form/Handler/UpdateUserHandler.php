<?php
declare(strict_types=1);

namespace App\UI\Form\Handler;

use App\Domain\Builder\UpdateUserBuilder;
use App\Domain\Entity\User;
use App\Domain\Repository\UserRepository;
use App\Service\Image\ImageThumbnailCreator;
use App\Service\Image\ImageUploader;
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
     * @var ImageUploader
     */
    private $imageUploader;

    /**
     * @var ImageThumbnailCreator
     */
    private $imageThumbnailCreator;

    /**
     * UpdateUserHandler constructor.
     *
     * @param UpdateUserBuilder $updateUserBuilder
     * @param ValidatorInterface $validator
     * @param SessionInterface $session
     * @param UserRepository $userRepository
     * @param ImageUploader $imageUploader
     * @param ImageThumbnailCreator $imageThumbnailCreator
     */
    public function __construct(
        UpdateUserBuilder $updateUserBuilder,
        ValidatorInterface $validator,
        SessionInterface $session,
        UserRepository $userRepository,
        ImageUploader $imageUploader,
        ImageThumbnailCreator $imageThumbnailCreator
    ) {
        $this->updateUserBuilder = $updateUserBuilder;
        $this->validator = $validator;
        $this->session = $session;
        $this->userRepository = $userRepository;
        $this->imageUploader = $imageUploader;
        $this->imageThumbnailCreator = $imageThumbnailCreator;
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
            $updateUserDTO = $form->getData();
            $user = $this->updateUserBuilder->create($user, $updateUserDTO);

            $errors = $this->validator->validate($user, null, ['userRegistration', 'User']);
            if (\count($errors) > 0) {
                foreach ($errors as $violation) {
                    $this->session->getFlashBag()->add('warning', $violation->getMessage());
                }
                return false;
            }

            $this->userRepository->save($user);

            $this->imageUploader->uploadFiles();
            $this->imageThumbnailCreator->createThumbnails();

            $this->session->getFlashBag()->add('success', 'Ton profil a bien été mise à jour');

            return true;
        }
        return false;
    }
}
