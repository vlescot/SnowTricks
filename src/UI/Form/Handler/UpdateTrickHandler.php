<?php
declare(strict_types = 1);

namespace App\UI\Form\Handler;

use App\Domain\Builder\UpdateTrickBuilder;
use App\Domain\CollectionManager\CollectionChecker\PictureCollectionChecker;
use App\Domain\Entity\Trick;
use App\Domain\Repository\PictureRepository;
use App\Domain\Repository\TrickRepository;
use App\UI\Service\Image\FolderChanger;
use App\UI\Service\Image\ImageRemover;
use App\UI\Service\Image\ImageThumbnailCreator;
use App\UI\Service\Image\ImageUploader;
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
     * @var PictureRepository
     */
    private $pictureRepository;

    /**
     * @var PictureCollectionChecker
     */
    private $pictureChecker;

    /**
     * @var ImageRemover
     */
    private $imageRemover;

    /**
     * @var ImageUploader
     */
    private $imageUploader;

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
     * @param PictureRepository $pictureRepository
     * @param PictureCollectionChecker $pictureChecker
     * @param ImageRemover $imageRemover
     * @param ImageUploader $imageUploader
     * @param ImageThumbnailCreator $thumbnailCreator
     * @param FolderChanger $folderChanger
     * @param UpdateTrickBuilder $updateTrickBuilder
     * @param ValidatorInterface $validator
     * @param SessionInterface $session
     */
    public function __construct(
        TrickRepository $trickRepository,
        PictureRepository $pictureRepository,
        PictureCollectionChecker $pictureChecker,
        ImageRemover $imageRemover,
        ImageUploader $imageUploader,
        ImageThumbnailCreator $thumbnailCreator,
        FolderChanger $folderChanger,
        UpdateTrickBuilder $updateTrickBuilder,
        ValidatorInterface $validator,
        SessionInterface $session
    ) {
        $this->trickRepository = $trickRepository;
        $this->pictureRepository = $pictureRepository;
        $this->pictureChecker = $pictureChecker;
        $this->imageRemover = $imageRemover;
        $this->imageUploader = $imageUploader;
        $this->thumbnailCreator = $thumbnailCreator;
        $this->folderChanger = $folderChanger;
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
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(FormInterface $form, Trick $trick)
    {
        if ($form->isSubmitted() && $form->isValid()) {

            dump($trick);

            $trick = $this->updateTrickBuilder->update($trick, $form->getData());

            $errors = $this->validator->validate($trick, null, ['edit_trick', 'Trick', 'Group']);
            if (\count($errors) > 0) {
                foreach ($errors as $violation) {
                    $this->session->getFlashBag()->add('warning', $violation->getMessage());
                }
                return false;
            }

            $this->trickRepository->save($trick);

            foreach ($this->pictureChecker->getDeletedObject() as $picture) {
                $this->pictureRepository->remove($picture);
            }

            $this->imageUploader->uploadFiles();
            $this->imageRemover->removeFiles();
            $this->thumbnailCreator->createThumbnails();
            $this->folderChanger->changeFilesDirectory();

            $this->session->set('slug', $trick->getSlug());
            $this->session->getFlashBag()->add('success', 'La figure a bien été modifiée !');

            dump($this->trickRepository->findOneBy(['slug' => $trick->getSlug()]));
            die;

            return true;
        }
        return false;
    }
}
