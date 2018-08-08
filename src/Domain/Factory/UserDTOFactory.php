<?php
declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\DTO\UpdateUserDTO;
use App\Domain\Entity\User;

class UserDTOFactory
{
    /**
     * @var PictureDTOFactory
     */
    private $pictureDTOFactory;

    /**
     * UserDTOFactory constructor.
     * @param PictureDTOFactory $pictureDTOFactory
     */
    public function __construct(PictureDTOFactory $pictureDTOFactory)
    {
        $this->pictureDTOFactory = $pictureDTOFactory;
    }


    public function create(User $user): UpdateUserDTO
    {
        return new UpdateUserDTO(
            $user->getUsername(),
            $user->getEmail(),
            $this->pictureDTOFactory->create($user->getPicture())
        );
    }
}
