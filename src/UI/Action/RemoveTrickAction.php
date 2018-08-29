<?php
declare(strict_types=1);

namespace App\UI\Action;

use App\Domain\Repository\PictureRepository;
use App\Domain\Repository\TrickRepository;
use App\UI\Service\Image\FolderRemover;
use App\UI\Service\Image\ImageRemover;
use App\UI\Responder\RemoveTrickResponder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/figure/supprimer", name="RemoveTrick")
 * @Method("POST")
 *
 * Class RemoveTrickAction
 * @package App\UI\Action
 */
class RemoveTrickAction
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
     * @var FolderRemover
     */
    private $folderRemover;

    /**
     * RemoveTrickAction constructor.
     *
     * @param TrickRepository $trickRepository
     * @param PictureRepository $pictureRepository
     * @param ImageRemover $imageRemover
     * @param FolderRemover $folderRemover
     */
    public function __construct(
        TrickRepository $trickRepository,
        PictureRepository $pictureRepository,
        ImageRemover $imageRemover,
        FolderRemover $folderRemover
    ) {
        $this->trickRepository = $trickRepository;
        $this->pictureRepository = $pictureRepository;
        $this->imageRemover = $imageRemover;
        $this->folderRemover = $folderRemover;
    }


    /**
     * @param Request $request
     * @param RemoveTrickResponder $responder
     *
     * @return Response
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function __invoke(Request $request, RemoveTrickResponder $responder): Response
    {
        $id = $request->request->get('id');
        $trick = $this->trickRepository->findOneBy(['id' => $id]);

        $this->folderRemover->removeFolder($trick->getMainPicture()->getPath());
        $this->trickRepository->remove($trick);

        return $responder();
    }
}
