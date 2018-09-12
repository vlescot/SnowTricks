<?php
declare(strict_types=1);

namespace App\Domain\Repository\Interfaces;

use App\Domain\Entity\Interfaces\TrickInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

interface TrickRepositoryInterface
{
    /**
     * TrickRepositoryInterface constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry);


    public function findAll(): array;

    /**
     * @param TrickInterface $trick
     */
    public function save(TrickInterface $trick): void;

    /**
     * @param TrickInterface $trick
     */
    public function remove(TrickInterface $trick): void;
}
