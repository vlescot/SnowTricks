<?php
declare(strict_types=1);

namespace App\Domain\DTO\Interfaces;

interface GroupDTOInterface
{
    /**
     * GroupDTOInterface constructor.
     *
     * @param string $name
     */
    public function __construct(string $name);
}
