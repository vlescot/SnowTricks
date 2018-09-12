<?php
declare(strict_types=1);

namespace App\Domain\Builder\Interfaces;

use App\Domain\DTO\Interfaces\TrickDTOInterface;
use App\Domain\Entity\Interfaces\TrickInterface;
use App\Service\Image\Interfaces\ImageUploadWarmerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

interface CreateTrickBuilderInterface
{
    /**
     * CreateTrickBuilderInterface constructor.
     *
     * @param PictureBuilderInterface $pictureBuilder
     * @param VideoBuilderInterface $videoBuilder
     * @param GroupBuilderInterface $groupBuilder
     * @param ImageUploadWarmerInterface $imageUploadWarmer
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        PictureBuilderInterface $pictureBuilder,
        VideoBuilderInterface $videoBuilder,
        GroupBuilderInterface $groupBuilder,
        ImageUploadWarmerInterface $imageUploadWarmer,
        TokenStorageInterface $tokenStorage
    );

    /**
     * @param TrickDTOInterface $trickDTO
     *
     * @return TrickInterface
     */
    public function create(TrickDTOInterface $trickDTO): TrickInterface;
}
