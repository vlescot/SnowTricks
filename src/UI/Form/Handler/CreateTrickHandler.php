<?php
declare(strict_types = 1);

namespace App\UI\Form\Handler;

use App\Domain\Builder\Interfaces\CreateTrickBuilderInterface;
use App\Domain\Repository\Interfaces\TrickRepositoryInterface;
use App\UI\Form\Handler\Interfaces\CreateTrickHandlerInterface;
use App\Service\Image\Interfaces\ImageThumbnailCreatorInterface;
use App\Service\Image\Interfaces\ImageUploaderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class CreateTrickHandler
 * @package App\UI\Form\Handler
 */
final class CreateTrickHandler implements CreateTrickHandlerInterface
{
    /**
     * @var TrickRepositoryInterface
     */
    private $trickRepository;

    /**
     * @var CreateTrickBuilderInterface
     */
    private $trickBuilder;

    /**
     * @var ImageUploaderInterface
     */
    private $imageUploader;

    /**
     * @var ImageThumbnailCreatorInterface
     */
    private $imageThumbnailCreator;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * CreateTrickHandler constructor.
     *
     * @param TrickRepositoryInterface $trickRepository
     * @param CreateTrickBuilderInterface $trickBuilder
     * @param ImageUploaderInterface $imageUploader
     * @param ImageThumbnailCreatorInterface $imageThumbnailCreator
     * @param ValidatorInterface $validator
     * @param SessionInterface $session
     */
    public function __construct(
        TrickRepositoryInterface $trickRepository,
        CreateTrickBuilderInterface $trickBuilder,
        ImageUploaderInterface $imageUploader,
        ImageThumbnailCreatorInterface $imageThumbnailCreator,
        ValidatorInterface $validator,
        SessionInterface $session
    ) {
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
     */
    public function handle(FormInterface $form): bool
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

            $this->trickRepository->save($trick);

            $this->imageUploader->uploadFiles();
            $this->imageThumbnailCreator->createThumbnails();

            $this->session->set('slug', $trick->getSlug());

            $this->session->getFlashBag()->add('success', 'La figure a bien été ajoutée !');
            
            return true;
        }
        return false;
    }
}
