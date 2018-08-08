<?php
declare(strict_types = 1);

namespace App\UI\Form\Handler;

use App\Domain\Builder\UpdateTrickBuilder;
use App\Domain\Entity\Trick;
use App\Domain\Repository\TrickRepository;
use App\Service\Image\FolderChanger;
use App\Service\Image\ImageRemover;
use App\Service\Image\ImageThumbnailCreator;
use App\Service\Image\ImageUploader;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class UpdateTrickHandler
{
    /**
     * @var TrickRepository
     */
    private $trickRepository;

    /**
     * @var ImageUploader
     */
    private $imageUploader;

    /**
     * @var ImageRemover
     */
    private $imageRemover;

    /**
     * @var ImageThumbnailCreator
     */
    private $thumbnailCreator;

    /**
     * @var FolderChanger
     */
    private $folderChanger;

    /**
     * @var UpdateTrickBuilder
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
     * UpdateTrickHandler constructor.
     *
     * @param TrickRepository $trickRepository
     * @param ImageUploader $imageUploader
     * @param ImageRemover $imageRemover
     * @param FolderChanger $folderChanger
     * @param ImageThumbnailCreator $thumbnailCreator
     * @param UpdateTrickBuilder $updateTrickBuilder
     * @param ValidatorInterface $validator
     * @param SessionInterface $session
     */
    public function __construct(
        TrickRepository $trickRepository,
        ImageUploader $imageUploader,
        ImageRemover $imageRemover,
        FolderChanger $folderChanger,
        ImageThumbnailCreator $thumbnailCreator,
        UpdateTrickBuilder $updateTrickBuilder,
        ValidatorInterface $validator,
        SessionInterface $session
    ) {
        $this->trickRepository = $trickRepository;
        $this->imageUploader = $imageUploader;
        $this->imageRemover = $imageRemover;
        $this->folderChanger = $folderChanger;
        $this->thumbnailCreator = $thumbnailCreator;
        $this->updateTrickBuilder = $updateTrickBuilder;
        $this->validator = $validator;
        $this->session = $session;
    }

    /**
     * @param FormInterface $form
     * @param Trick $trick
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function handle(FormInterface $form, Trick $trick)
    {
        if ($form->isSubmitted() && $form->isValid()) {

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
