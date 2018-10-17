<?php
declare(strict_types = 1);

namespace App\UI\Form\Handler;

use App\Domain\Builder\Interfaces\UpdateTrickBuilderInterface;
use App\Domain\Entity\Interfaces\TrickInterface;
use App\Domain\Repository\Interfaces\TrickRepositoryInterface;
use App\UI\Form\Handler\Interfaces\UpdateTrickHandlerInterface;
use App\App\Image\Interfaces\FolderChangerInterface;
use App\App\Image\Interfaces\ImageRemoverInterface;
use App\App\Image\Interfaces\ImageThumbnailCreatorInterface;
use App\App\Image\Interfaces\ImageUploaderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UpdateTrickHandler implements UpdateTrickHandlerInterface
{
    /**
     * @var TrickRepositoryInterface
     */
    private $trickRepository;

    /**
     * @var ImageRemoverInterface
     */
    private $imageRemover;

    /**
     * @var ImageUploaderInterface
     */
    private $imageUploader;

    /**
     * @var ImageThumbnailCreatorInterface
     */
    private $thumbnailCreator;

    /**
     * @var FolderChangerInterface
     */
    private $folderChanger;

    /**
     * @var UpdateTrickBuilderInterface
     */
    private $updateTrickBuilder;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var SessionInterface
     */
    private $session;


    /**
     * {@inheritdoc}
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
    ) {
        $this->trickRepository = $trickRepository;
        $this->imageRemover = $imageRemover;
        $this->imageUploader = $imageUploader;
        $this->thumbnailCreator = $thumbnailCreator;
        $this->folderChanger = $folderChanger;
        $this->updateTrickBuilder = $updateTrickBuilder;
        $this->validator = $validator;
        $this->session = $session;
    }


    /**
     * {@inheritdoc}
     */
    public function handle(FormInterface $form, TrickInterface $trick): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {

            $trick = $this->updateTrickBuilder->update($trick, $form->getData());

            $errors = $this->validator->validate($trick, null, ['trick']);
            if (\count($errors) > 0) {
                foreach ($errors as $violation) {
                    $this->session->getFlashBag()->add('warning', $violation->getMessage());
                }
                return false;
            }

            $this->trickRepository->save($trick);

            $this->imageUploader->uploadFiles();
            $this->imageRemover->removeFiles();
            $this->thumbnailCreator->createThumbnails();
            $this->folderChanger->changeFilesDirectory();

            $this->session->set('slug', $trick->getSlug());
            $this->session->getFlashBag()->add('success', 'La figure a bien été modifiée !');

            return true;
        }
        return false;
    }
}
