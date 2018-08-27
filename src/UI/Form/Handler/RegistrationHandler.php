<?php
declare(strict_types = 1);

namespace App\UI\Form\Handler;

use App\Domain\Builder\UserBuilder;
use App\Domain\Repository\UserRepository;
use App\Service\Image\ImageThumbnailCreator;
use App\Service\Image\ImageUploader;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegistrationHandler
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserBuilder
     */
    private $userBuilder;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var ImageUploader
     */
    private $imageUploader;

    /**
     * @var ImageThumbnailCreator
     */
    private $thumbnailCreator;

    /**
     * RegistrationHandler constructor.
     *
     * @param ValidatorInterface $validator
     * @param UserRepository $userRepository
     * @param UserBuilder $userBuilder
     * @param SessionInterface $session
     * @param ImageUploader $imageUploader
     * @param ImageThumbnailCreator $thumbnailCreator
     */
    public function __construct(
        ValidatorInterface $validator,
        UserRepository $userRepository,
        UserBuilder $userBuilder,
        SessionInterface $session,
        ImageUploader $imageUploader,
        ImageThumbnailCreator $thumbnailCreator
    ) {
        $this->validator = $validator;
        $this->userRepository = $userRepository;
        $this->userBuilder = $userBuilder;
        $this->session = $session;
        $this->imageUploader = $imageUploader;
        $this->thumbnailCreator = $thumbnailCreator;
    }


    /**
     * @param Form $form
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function handle(Form $form) :bool
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

            $this->session->getFlashBag()->add('success', 'Bienvenue parmis nous, ' . $form->get('username')->getData() . '. Un e-mail vient de t\'être envoyé pour confirmé ton compte');

            return true;
        }
        return false;
    }
}
