<?php
declare(strict_types=1);

namespace App\UI\Form\Handler\Interfaces;

use App\Domain\Builder\Interfaces\PictureBuilderInterface;
use App\Domain\Builder\Interfaces\UpdateTrickBuilderInterface;
use App\Domain\Entity\Interfaces\TrickInterface;
use App\Domain\Repository\Interfaces\TrickRepositoryInterface;
use App\App\Image\Interfaces\FolderChangerInterface;
use App\App\Image\Interfaces\ImageRemoverInterface;
use App\App\Image\Interfaces\ImageThumbnailCreatorInterface;
use App\App\Image\Interfaces\ImageUploaderInterface;
use App\App\Image\Interfaces\ImageUploadWarmerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

interface UpdateTrickHandlerInterface
{

    /**
     * UpdateTrickHandlerInterface constructor.
     *
     * @param TrickRepositoryInterface $trickRepository
     * @param ImageRemoverInterface $imageRemover
     * @param ImageUploaderInterface $imageUploader
     * @param ImageThumbnailCreatorInterface $thumbnailCreator
     * @param FolderChangerInterface $folderChanger
     * @param UpdateTrickBuilderInterface $updateTrickBuilder
     * @param ValidatorInterface $validator
     * @param SessionInterface $session
     */
    public function __construct(
        TrickRepositoryInterface $trickRepository,
        ImageRemoverInterface $imageRemover,
        ImageUploaderInterface $imageUploader,
        ImageThumbnailCreatorInterface $thumbnailCreator,
        FolderChangerInterface $folderChanger,
        UpdateTrickBuilderInterface $updateTrickBuilder,
        ValidatorInterface $validator,
        SessionInterface $session
    );

    /**
     * @param FormInterface $form
     * @param TrickInterface $trick
     *
     * @return bool
     */
    public function handle(FormInterface $form, TrickInterface $trick): bool;
}
