<?php
declare(strict_types = 1);

namespace App\UI\Form\Handler;

use App\Domain\Builder\CreateTrickBuilder;
use App\Domain\Entity\User;
use App\Domain\Repository\TrickRepository;
use App\Domain\Repository\UserRepository;
use App\Service\Image\ImageThumbnailCreator;
use App\Service\Image\ImageUploader;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class CreateTrickHandler
 * @package App\UI\Form\Handler
 */
class CreateTrickHandler
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var TrickRepository
     */
    private $trickRepository;

    /**
     * @var CreateTrickBuilder
     */
    private $trickBuilder;

    /**
     * @var ImageUploader
     */
    private $imageUploader;

    /**
     * @var ImageThumbnailCreator
     */
    private $imageThumbnailCreator;

    /**
     * @var ValidatorInterface
     *
     */
    private $validator;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * CreateTrickHandler constructor.
     *
     * @param UserRepository $userRepository
     * @param TrickRepository $trickRepository
     * @param CreateTrickBuilder $trickBuilder
     * @param ImageUploader $imageUploader
     * @param ImageThumbnailCreator $imageThumbnailCreator
     * @param ValidatorInterface $validator
     * @param SessionInterface $session
     */
    public function __construct(
        UserRepository $userRepository,
        TrickRepository $trickRepository,
        CreateTrickBuilder $trickBuilder,
        ImageUploader $imageUploader,
        ImageThumbnailCreator $imageThumbnailCreator,
        ValidatorInterface $validator,
        SessionInterface $session
    ) {
        $this->userRepository = $userRepository;
        $this->trickRepository = $trickRepository;
        $this->trickBuilder = $trickBuilder;
        $this->imageUploader = $imageUploader;
        $this->imageThumbnailCreator = $imageThumbnailCreator;
        $this->validator = $validator;
        $this->session = $session;
    }

    /**
     * @param FormInterface $form
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function handle(FormInterface $form)
    {
        if ($form->isSubmitted() && $form->isValid()) {

            $trick = $this->trickBuilder->create($form->getData());

            $errors = $this->validator->validate($trick, null, ['edit_trick', 'Trick', 'Group']);
            if (\count($errors) > 0) {
                foreach ($errors as $violation) {
                    $this->session->getFlashBag()->add('warning', $violation->getMessage());
                }
                return false;
            }

            try {
                $this->trickRepository->save($trick);
            } catch (UniqueConstraintViolationException $e) {
                $this->session->getFlashBag()->add('warning', 'Ce trick existe déjà.');
                return false;
            }

            $this->imageUploader->uploadFiles();
            $this->imageThumbnailCreator->createThumbnails();

            $this->session->set('slug', $trick->getSlug());
            
            $this->session->getFlashBag()->add('success', 'La figure a bien été ajoutée !');
            
            return true;
        }
        return false;
    }
}
