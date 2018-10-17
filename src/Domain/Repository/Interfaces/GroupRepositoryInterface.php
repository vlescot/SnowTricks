<?php
declare(strict_types=1);

namespace App\Domain\Repository\Interfaces;

use Symfony\Bridge\Doctrine\RegistryInterface;

interface GroupRepositoryInterface
{
    /**
     * @return mixed
     */
    public function __construct(RegistryInterface $registry);
}
