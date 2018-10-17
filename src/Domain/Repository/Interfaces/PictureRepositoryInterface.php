<?php
declare(strict_types=1);

namespace App\Domain\Repository\Interfaces;

use App\Domain\Entity\Interfaces\PictureInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

interface PictureRepositoryInterface
{
    /**
     * PictureRepositoryInterface constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry);

    /**
     * @param PictureInterface $picture
     */
    public function remove(PictureInterface $picture): void;
}
