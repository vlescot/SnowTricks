<?php
declare(strict_types=1);

namespace App\Domain\Builder\Interfaces;

use App\Domain\DTO\Interfaces\UserDTOInterface;
use App\Service\Image\Interfaces\ImageUploadWarmerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

interface UserBuilderInterface
{

    /**
     * UserBuilderInterface constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param ImageUploadWarmerInterface $imageUploadWarmer
     * @param PictureBuilderInterface $pictureBuilder
     */
    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        ImageUploadWarmerInterface $imageUploadWarmer,
        PictureBuilderInterface $pictureBuilder
    );

    /**
     * @param UserDTOInterface $userDTO
     *
     * @return UserInterface
     */
    public function create(UserDTOInterface $userDTO): UserInterface;
}
