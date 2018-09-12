<?php
declare(strict_types=1);

namespace App\Domain\Factory\Interfaces;

use App\Domain\DTO\Interfaces\UpdateUserDTOInterface;
use Symfony\Component\Security\Core\User\UserInterface;

interface UserDTOFactoryInterface
{
    /**
     * UserDTOFactoryInterface constructor.
     *
     * @param PictureDTOFactoryInterface $pictureDTOFactory
     */
    public function __construct(PictureDTOFactoryInterface $pictureDTOFactory);

    /**
     * @param UserInterface $user
     *
     * @return UpdateUserDTOInterface
     */
    public function create(UserInterface $user): UpdateUserDTOInterface;
}
