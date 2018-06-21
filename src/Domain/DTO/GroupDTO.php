<?php

namespace App\Domain\DTO;

use App\Domain\Entity\Trick;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @var \ArrayAccess
     */
    public $tricks;

    /**
     * GroupDTO constructor.
     * @param string $name
     * @param \ArrayAccess $tricks
     */
    public function __construct(string $name, \ArrayAccess $tricks)
    {
        $this->name = $name;
        $this->tricks = $tricks;
    }
}
