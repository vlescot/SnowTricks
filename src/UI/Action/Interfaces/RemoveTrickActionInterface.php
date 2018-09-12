<?php
declare(strict_types=1);

namespace App\UI\Action\Interfaces;

use App\Domain\Repository\Interfaces\TrickRepositoryInterface;
use App\UI\Responder\Interfaces\RemoveTrickResponderInterface;
use App\Service\Image\Interfaces\FolderRemoverInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface RemoveTrickActionInterface
{
    /**
     * RemoveTrickActionInterface constructor.
     *
     * @param TrickRepositoryInterface $trickRepository
     * @param FolderRemoverInterface $folderRemover
     */
    public function __construct(
        TrickRepositoryInterface $trickRepository,
        FolderRemoverInterface $folderRemover
    );

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request): Response;
}
