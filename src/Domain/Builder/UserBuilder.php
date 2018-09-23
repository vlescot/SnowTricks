<?php
declare(strict_types=1);

namespace App\Domain\Builder;

use App\Domain\Builder\Interfaces\PictureBuilderInterface;
use App\Domain\Builder\Interfaces\UserBuilderInterface;
use App\Domain\DTO\Interfaces\UserDTOInterface;
use App\Domain\Entity\User;
use App\Service\Image\Interfaces\ImageUploadWarmerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserBuilder implements UserBuilderInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var ImageUploadWarmerInterface
     */
    private $imageUploadWarmer;

    /**
     * @var PictureBuilderInterface
     */
    private $pictureBuilder;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        ImageUploadWarmerInterface $imageUploadWarmer,
        PictureBuilderInterface $pictureBuilder
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->imageUploadWarmer = $imageUploadWarmer;
        $this->pictureBuilder = $pictureBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function create(UserDTOInterface $userDTO): UserInterface
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
