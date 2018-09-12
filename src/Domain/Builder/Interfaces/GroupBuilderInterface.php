<?php
declare(strict_types=1);

namespace App\Domain\Builder\Interfaces;

interface GroupBuilderInterface
{
    /**
     * @param \ArrayAccess $groups
     * @param array $groupDTO
     *
     * @return \ArrayAccess
     */
    public function create(\ArrayAccess $groups, array $groupDTO): \ArrayAccess;
}
