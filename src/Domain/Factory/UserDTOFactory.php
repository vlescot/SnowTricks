<?php
declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\DTO\Interfaces\UpdateUserDTOInterface;
use App\Domain\DTO\UpdateUserDTO;
use App\Domain\Factory\Interfaces\PictureDTOFactoryInterface;
use App\Domain\Factory\Interfaces\UserDTOFactoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserDTOFactory implements UserDTOFactoryInterface
{
    /**
     * @var PictureDTOFactory
     */
    private $pictureDTOFactory;

    /**
     * UserDTOFactory constructor.
     *
     * @param PictureDTOFactoryInterface $pictureDTOFactory
     */
    public function __construct(PictureDTOFactoryInterface $pictureDTOFactory)
    {
        $this->pictureDTOFactory = $pictureDTOFactory;
    }

    /**
     * @param UserInterface $user
     *
     * @return UpdateUserDTOInterface
     */
    public function create(UserInterface $user): UpdateUserDTOInterface
    {
        return new UpdateUserDTO(
            $user->getUsername(),
            null,
            $user->getEmail(),
            $this->pictureDTOFactory->create($user->getPicture())
        );
    }
}
