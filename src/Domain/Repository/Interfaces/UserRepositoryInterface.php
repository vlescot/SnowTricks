<?php
declare(strict_types=1);

namespace App\Domain\Repository\Interfaces;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\User\UserInterface;

interface UserRepositoryInterface
{
    /**
     * UserRepositoryInterface constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry);

    /**
     * @param $token
     *
     * @return UserInterface
     */
    public function loadUserByToken($token): UserInterface;

    /**
     * @param UserInterface $user
     */
    public function save(UserInterface $user): void;
}
