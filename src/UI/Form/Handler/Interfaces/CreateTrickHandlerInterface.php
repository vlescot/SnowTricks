<?php
declare(strict_types=1);

namespace App\UI\Form\Handler\Interfaces;

use App\Domain\Builder\Interfaces\CreateTrickBuilderInterface;
use App\Domain\Repository\Interfaces\TrickRepositoryInterface;
use App\Service\Image\Interfaces\ImageThumbnailCreatorInterface;
use App\Service\Image\Interfaces\ImageUploaderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

interface CreateTrickHandlerInterface
{
    /**
     * CreateTrickHandlerInterface constructor.
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
    );

    /**
     * @param FormInterface $form
     *
     * @return bool
     */
    public function handle(FormInterface $form): bool;
}
