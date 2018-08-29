<?php
declare(strict_types=1);

namespace App\Domain\Builder;

use App\Domain\DTO\UserDTO;
use App\Domain\Entity\User;
use App\UI\Service\Image\ImageUploadWarmer;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserBuilder
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var ImageUploadWarmer
     */
    private $imageUploadWarmer;

    /**
     * @var PictureBuilder
     */
    private $pictureBuilder;

    /**
     * UserBuilder constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param ImageUploadWarmer $imageUploadWarmer
     * @param PictureBuilder $pictureBuilder
     */
    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        ImageUploadWarmer $imageUploadWarmer,
        PictureBuilder $pictureBuilder
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->imageUploadWarmer = $imageUploadWarmer;
        $this->pictureBuilder = $pictureBuilder;
    }

    /**
     * @param UserDTO $userDTO
     *
     * @return User
     *
     * @throws \Exception
     */
    public function create(UserDTO $userDTO)
    {
        if ($userDTO->picture->file instanceof UploadedFile) {
            $this->imageUploadWarmer->initialize('user', $userDTO->username);
            $picture = $this->pictureBuilder->create($userDTO->picture, false, true);
        }

        $user = new User();

        $password = $this->passwordEncoder->encodePassword($user, $userDTO->password);

        $user->registration(
            $userDTO->username,
            $userDTO->email,
            $password,
            $picture ?? null
        );

        return $user;
    }
}
