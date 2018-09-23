<?php
declare(strict_types=1);

namespace App\Domain\Builder;

use App\Domain\Builder\Interfaces\PictureBuilderInterface;
use App\Domain\Builder\Interfaces\UpdateUserBuilderInterface;
use App\Domain\DTO\Interfaces\UpdateUserDTOInterface;
use App\Service\Image\Interfaces\ImageRemoverInterface;
use App\Service\Image\Interfaces\ImageUploadWarmerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class UpdateUserBuilder implements UpdateUserBuilderInterface
{
    /**
     * @var ImageUploadWarmerInterface
     */
    private $imageUploadWarmer;

    /**
     * @var PictureBuilderInterface
     */
    private $pictureBuilder;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var ImageRemoverInterface
     */
    private $imageRemover;

    /**
     * {@inheritdoc}
     */
    public function __construct(
        ImageUploadWarmerInterface $imageUploadWarmer,
        PictureBuilderInterface $pictureBuilder,
        UserPasswordEncoderInterface $passwordEncoder,
        ImageRemoverInterface $imageRemover
    ) {
        $this->imageUploadWarmer = $imageUploadWarmer;
        $this->pictureBuilder = $pictureBuilder;
        $this->passwordEncoder = $passwordEncoder;
        $this->imageRemover = $imageRemover;
    }


    /**
     * {@inheritdoc}
     */
    public function create(UserInterface $user, UpdateUserDTOInterface $updateUserDTO): UserInterface
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
