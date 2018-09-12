<?php
declare(strict_types = 1);

namespace App\UI\Form\Handler;

use App\Domain\Builder\Interfaces\PictureBuilderInterface;
use App\Domain\Builder\Interfaces\UpdateTrickBuilderInterface;
use App\Domain\Entity\Interfaces\TrickInterface;
use App\Domain\Repository\Interfaces\TrickRepositoryInterface;
use App\Service\Image\Interfaces\ImageUploadWarmerInterface;
use App\UI\Form\Handler\Interfaces\UpdateTrickHandlerInterface;
use App\Service\Image\Interfaces\FolderChangerInterface;
use App\Service\Image\Interfaces\ImageRemoverInterface;
use App\Service\Image\Interfaces\ImageThumbnailCreatorInterface;
use App\Service\Image\Interfaces\ImageUploaderInterface;
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
     * @var ImageUploadWarmerInterface
     */
    private $imageUploadWarmer;

    /**
     * @var PictureBuilderInterface
     */
    private $pictureBuilder;


    /**
     * UpdateTrickHandler constructor.
     *
     * @param TrickRepositoryInterface $trickRepository
     * @param ImageRemoverInterface $imageRemover
     * @param ImageUploaderInterface $imageUploader
     * @param ImageThumbnailCreatorInterface $thumbnailCreator
     * @param FolderChangerInterface $folderChanger
     * @param UpdateTrickBuilderInterface $updateTrickBuilder
     * @param ValidatorInterface $validator
     * @param SessionInterface $session
     * @param ImageUploadWarmerInterface $imageUploadWarmer
     * @param PictureBuilderInterface $pictureBuilder
     */
    public function __construct(
        TrickRepositoryInterface $trickRepository,
        ImageRemoverInterface $imageRemover,
        ImageUploaderInterface $imageUploader,
        ImageThumbnailCreatorInterface $thumbnailCreator,
        FolderChangerInterface $folderChanger,
        UpdateTrickBuilderInterface $updateTrickBuilder,
        ValidatorInterface $validator,
        SessionInterface $session,
        ImageUploadWarmerInterface $imageUploadWarmer,
        PictureBuilderInterface $pictureBuilder
    ) {
        $this->trickRepository = $trickRepository;
        $this->imageRemover = $imageRemover;
        $this->imageUploader = $imageUploader;
        $this->thumbnailCreator = $thumbnailCreator;
        $this->folderChanger = $folderChanger;
        $this->updateTrickBuilder = $updateTrickBuilder;
        $this->validator = $validator;
        $this->session = $session;
        $this->imageUploadWarmer = $imageUploadWarmer;
        $this->pictureBuilder = $pictureBuilder;
    }


    /**
     * @param FormInterface $form
     * @param TrickInterface $trick
     *
     * @return bool
     */
    public function handle(FormInterface $form, TrickInterface $trick): bool
    {
        if ($form->isSubmitted() && $form->isValid()) {
            $trickTitle = $form->get('title')->getData();

            if ($trickTitle !== $trick->getTitle()) {    // Change the pictures's folder
                $this->imageUploadWarmer->initialize('trick', $trickTitle);

                $updatePictureInfo = $this->imageUploadWarmer->getUpdateImageInfo();
                $this->folderChanger->folderToChange($trick->getMainPicture()->getPath(), $updatePictureInfo['path']);

                $trick->getMainPicture()->update($updatePictureInfo['path']);
                foreach ($trick->getPictures() as $picture) {
                    $picture->update($updatePictureInfo['path']);
                }
            }


            $trick = $this->updateTrickBuilder->update($trick, $form->getData());

            $errors = $this->validator->validate($trick, null, ['edit_trick', 'Trick', 'Group']);
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
