<?php
declare(strict_types=1);

namespace App\Domain\CollectionManager\Interfaces;

use App\Domain\Builder\Interfaces\PictureBuilderInterface;
use App\Domain\Builder\Interfaces\VideoBuilderInterface;
use App\App\Image\Interfaces\ImageRemoverInterface;

interface CollectionUpdatePrepareInterface
{
    /**
     * CollectionUpdatePrepareInterface constructor.
     *
     * @param CollectionCheckerInterface $collectionChecker
     * @param ImageRemoverInterface $imageRemover
     * @param PictureBuilderInterface $pictureBuilder
     * @param VideoBuilderInterface $videoBuilder
     */
    public function __construct(
        CollectionCheckerInterface $collectionChecker,
        ImageRemoverInterface $imageRemover,
        PictureBuilderInterface $pictureBuilder,
        VideoBuilderInterface $videoBuilder
    );

    /**
     * @param array $collection
     * @param array $collectionDTO
     *
     * @return array
     */
    public function prepare(array $collection, array $collectionDTO): array;
}
