<?php
declare(strict_types=1);

namespace App\Domain\Builder\Interfaces;

use App\Domain\DTO\Interfaces\UpdateUserDTOInterface;
use App\Service\Image\Interfaces\ImageRemoverInterface;
use App\Service\Image\Interfaces\ImageUploadWarmerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

interface UpdateUserBuilderInterface
{
    /**
     * UpdateUserBuilder constructor.
     *
     * @param ImageUploadWarmerInterface $imageUploadWarmer
     * @param PictureBuilderInterface $pictureBuilder
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param ImageRemoverInterface $imageRemover
     */
    public function __construct(
        ImageUploadWarmerInterface $imageUploadWarmer,
        PictureBuilderInterface $pictureBuilder,
        UserPasswordEncoderInterface $passwordEncoder,
        ImageRemoverInterface $imageRemover
    );


    /**
     * @param UserInterface $user
     * @param UpdateUserDTOInterface $updateUserDTO
     *
     * @return UserInterface
     */
    public function create(UserInterface $user, UpdateUserDTOInterface $updateUserDTO): UserInterface;
}
