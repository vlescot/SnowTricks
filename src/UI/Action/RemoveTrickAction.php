<?php
declare(strict_types=1);

namespace App\UI\Action;

use App\Domain\Repository\Interfaces\TrickRepositoryInterface;
use App\UI\Action\Interfaces\RemoveTrickActionInterface;
use App\Service\Image\Interfaces\FolderRemoverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     "/figure/supprimer",
 *     name="RemoveTrick",
 *     methods={"POST"}
 * )
 *
 * Class RemoveTrickAction
 * @package App\UI\Action
 */
final class RemoveTrickAction implements RemoveTrickActionInterface
{
    /**
     * @var TrickRepositoryInterface
     */
    private $trickRepository;

    /**
     * @var FolderRemoverInterface
     */
    private $folderRemover;

    /**
     * @inheritdoc
     */
    public function __construct(
        TrickRepositoryInterface $trickRepository,
        FolderRemoverInterface $folderRemover
    ) {
        $this->trickRepository = $trickRepository;
        $this->folderRemover = $folderRemover;
    }


    /**
     * @inheritdoc
     */
    public function __invoke(Request $request): Response
    {
        $id = $request->request->get('id');
        $trick = $this->trickRepository->findOneBy(['id' => $id]);

        $this->folderRemover->removeFolder($trick->getMainPicture()->getPath());
        $this->trickRepository->remove($trick);

        return new Response(null, 200);
    }
}
