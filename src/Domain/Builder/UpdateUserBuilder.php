<?php
declare(strict_types=1);

namespace App\Domain\Builder;

use App\Domain\DTO\UpdateUserDTO;
use App\Domain\Entity\User;
use App\Domain\Repository\PictureRepository;
use App\UI\Service\Image\ImageRemover;
use App\UI\Service\Image\ImageUploadWarmer;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UpdateUserBuilder
{
    /**
     * @var ImageUploadWarmer
     */
    private $imageUploadWarmer;

    /**
     * @var PictureBuilder
     */
    private $pictureBuilder;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var ImageRemover
     */
    private $imageRemover;

    /**
     * UpdateUserBuilder constructor.
     *
     * @param ImageUploadWarmer $imageUploadWarmer
     * @param PictureBuilder $pictureBuilder
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param ImageRemover $imageRemover
     */
    public function __construct(
        ImageUploadWarmer $imageUploadWarmer,
        PictureBuilder $pictureBuilder,
        UserPasswordEncoderInterface $passwordEncoder,
        ImageRemover $imageRemover
    ) {
        $this->imageUploadWarmer = $imageUploadWarmer;
        $this->pictureBuilder = $pictureBuilder;
        $this->passwordEncoder = $passwordEncoder;
        $this->imageRemover = $imageRemover;
    }


    /**
     * @param User $user
     * @param UpdateUserDTO $updateUserDTO
     *
     * @return User
     *
     * @throws \Exception
     */
    public function create(User $user, UpdateUserDTO $updateUserDTO)
    {
        if ($updateUserDTO->picture->file instanceof UploadedFile) {
            $this->imageUploadWarmer->initialize('user', '');
            $picture = $this->pictureBuilder->create($updateUserDTO->picture, false, true);

            $this->imageRemover->addFileToRemove($user->getPicture());
            $this->imageRemover->removeFiles();
        }

        if (is_string($updateUserDTO->password)) {
            $password = $this->passwordEncoder->encodePassword($user, $updateUserDTO->password);
        }

        $user->update(
            $updateUserDTO->email ?? $user->getEmail(),
            $password ?? $user->getPassword(),
            $picture ?? $user->getPicture()
        );

        return $user;
    }
}
