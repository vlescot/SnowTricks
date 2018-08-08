<?php
declare(strict_types = 1);

namespace App\Domain\DTO;

/**
 * Class GroupDTO.
 */
class GroupDTO
{
    /**
     * @var string
     */
    public $name;

    /**
     * GroupDTO constructor.
     * @param string $name
     * @param \ArrayAccess $tricks
     */
    public function __construct(
        string $name
    ) {
        $this->name = $name;
    }
}
