<?php
declare(strict_types = 1);

namespace App\Domain\Builder;

use App\Domain\DTO\PictureDTO;
use App\Domain\DTO\TrickDTO;
use App\Domain\Entity\Trick;
use App\Domain\Entity\User;
use App\Domain\Repository\UserRepository;
use App\UI\Service\Image\ImageUploadWarmer;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class TrickBuilder
 * @package App\Domain\Builder
 */
class CreateTrickBuilder
{
    private $userRepository;
    /**
     * @var PictureBuilder
     */
    private $pictureBuilder;

    /**
     * @var VideoBuilder
     */
    private $videoBuilder;

    /**
     * @var GroupBuilder
     */
    private $groupBuilder;

    /**
     * @var ImageUploadWarmer
     */
    private $imageUploadWarmer;

    /**
     * @var User
     */
    private $user;

    /**
     * CreateTrickBuilder constructor.
     *
     * @param UserRepository $userRepository
     * @param PictureBuilder $pictureBuilder
     * @param VideoBuilder $videoBuilder
     * @param GroupBuilder $groupBuilder
     * @param ImageUploadWarmer $imageUploadWarmer
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        UserRepository $userRepository,
        PictureBuilder $pictureBuilder,
        VideoBuilder $videoBuilder,
        GroupBuilder $groupBuilder,
        ImageUploadWarmer $imageUploadWarmer,
        TokenStorageInterface $tokenStorage
    ) {
        $this->userRepository = $userRepository;
        $this->pictureBuilder = $pictureBuilder;
        $this->videoBuilder = $videoBuilder;
        $this->groupBuilder = $groupBuilder;
        $this->imageUploadWarmer = $imageUploadWarmer;
        $this->user = $tokenStorage->getToken()->getUser();
    }

    /**
     * @param TrickDTO $trickDTO
     *
     * @return Trick
     *
     * @throws \Exception
     */
    public function create(TrickDTO $trickDTO)
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
