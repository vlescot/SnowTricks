<?php
declare(strict_types=1);

namespace App\Domain\Builder\Interfaces;

use App\Service\CollectionManager\Interfaces\CollectionUpdatePrepareInterface;
use App\Domain\DTO\Interfaces\TrickDTOInterface;
use App\Domain\Entity\Interfaces\TrickInterface;
use App\Service\Image\Interfaces\FolderChangerInterface;
use App\Service\Image\Interfaces\ImageRemoverInterface;
use App\Service\Image\Interfaces\ImageUploadWarmerInterface;

interface UpdateTrickBuilderInterface
{
    /**
     * UpdateTrickBuilderInterface constructor.
     *
     * @param CollectionUpdatePrepareInterface $collectionPrepare
     * @param ImageUploadWarmerInterface $imageUploadWarmer
     * @param ImageRemoverInterface $imageRemover
     * @param FolderChangerInterface $folderChanger
     * @param GroupBuilderInterface $groupBuilder
     * @param PictureBuilderInterface $pictureBuilder
     */
    public function __construct(
        CollectionUpdatePrepareInterface $collectionPrepare,
        ImageUploadWarmerInterface $imageUploadWarmer,
        ImageRemoverInterface $imageRemover,
        FolderChangerInterface $folderChanger,
        GroupBuilderInterface $groupBuilder,
        PictureBuilderInterface $pictureBuilder
    );

    /**
     * @param TrickInterface $trick
     * @param TrickDTOInterface $trickDTO
     *
     * @return TrickInterface
     */
    public function update(TrickInterface $trick, TrickDTOInterface $trickDTO): TrickInterface;
}
