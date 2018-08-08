<?php
declare(strict_types=1);

namespace App\Domain\Builder;

use App\Domain\DTO\UpdateUserDTO;
use App\Domain\Entity\User;
use App\Service\Image\ImageUploadWarmer;
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
     * UpdateUserBuilder constructor.
     *
     * @param ImageUploadWarmer $imageUploadWarmer
     * @param PictureBuilder $pictureBuilder
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(
        ImageUploadWarmer $imageUploadWarmer,
        PictureBuilder $pictureBuilder,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->imageUploadWarmer = $imageUploadWarmer;
        $this->pictureBuilder = $pictureBuilder;
        $this->passwordEncoder = $passwordEncoder;
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
            $this->imageUploadWarmer->initialize('user', $updateUserDTO->username);
            $picture = $this->pictureBuilder->create($updateUserDTO->picture, false, true);
        }

        $password = $this->passwordEncoder->encodePassword($user, $updateUserDTO->password);

        $user->update(
            $updateUserDTO->email,
            $password,
            $picture ?? $updateUserDTO->picture
        );

        return $user;
    }
}
