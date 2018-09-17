<?php
declare(strict_types=1);

namespace App\Service\CollectionManager\Interfaces;

interface CollectionCheckerInterface
{
    /**
     * @param array $pictures
     * @param array $picturesDTO
     * @param string $className
     */
    public function compare(array $pictures, array $picturesDTO, string $className): void;

    /**
     * @return array
     */
    public function getNewObjects(): array;

    /**
     * @return array
     */
    public function getDeletedObjects(): array;
}
