<?php
declare(strict_types = 1);

namespace App\Domain\Builder;

use App\Domain\Builder\Interfaces\CreateTrickBuilderInterface;
use App\Domain\Builder\Interfaces\GroupBuilderInterface;
use App\Domain\Builder\Interfaces\PictureBuilderInterface;
use App\Domain\Builder\Interfaces\VideoBuilderInterface;
use App\Domain\DTO\Interfaces\TrickDTOInterface;
use App\Domain\Entity\Interfaces\TrickInterface;
use App\Domain\Entity\Trick;
use App\Service\Image\Interfaces\ImageUploadWarmerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class TrickBuilder
 * @package App\Domain\Builder
 */
final class CreateTrickBuilder implements CreateTrickBuilderInterface
{
    /**
     * @var PictureBuilderInterface
     */
    private $pictureBuilder;

    /**
     * @var VideoBuilderInterface
     */
    private $videoBuilder;

    /**
     * @var GroupBuilderInterface
     */
    private $groupBuilder;

    /**
     * @var ImageUploadWarmerInterface
     */
    private $imageUploadWarmer;

    /**
     * @var UserInterface
     */
    private $user;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        PictureBuilderInterface $pictureBuilder,
        VideoBuilderInterface $videoBuilder,
        GroupBuilderInterface $groupBuilder,
        ImageUploadWarmerInterface $imageUploadWarmer,
        TokenStorageInterface $tokenStorage
    ) {
        $this->pictureBuilder = $pictureBuilder;
        $this->videoBuilder = $videoBuilder;
        $this->groupBuilder = $groupBuilder;
        $this->imageUploadWarmer = $imageUploadWarmer;
        $this->user = $tokenStorage->getToken()->getUser();
    }

    /**
     * {@inheritdoc}
     */
    public function create(TrickDTOInterface $trickDTO): TrickInterface
    {
        $this->imageUploadWarmer->initialize('trick', $trickDTO->title);

        return new Trick(
            $trickDTO->title,
            $trickDTO->description,
            $this->user,
            $this->pictureBuilder->create($trickDTO->mainPicture, false, true),
            $this->pictureBuilder->create($trickDTO->pictures, true),
            $this->videoBuilder->create($trickDTO->videos, true),
            $this->groupBuilder->create($trickDTO->groups, $trickDTO->newGroups)
        );
    }
}
