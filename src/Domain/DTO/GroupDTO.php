<?php
declare(strict_types = 1);

namespace App\Domain\DTO;

use App\Domain\DTO\Interfaces\GroupDTOInterface;

final class GroupDTO implements GroupDTOInterface
{
    /**
     * @var string
     */
    public $name;

    /**
     * GroupDTO constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
