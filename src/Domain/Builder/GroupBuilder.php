<?php
declare(strict_types = 1);

namespace App\Domain\Builder;

use App\Domain\Entity\Group;

class GroupBuilder
{
    /**
     * @param \ArrayAccess $groups
     * @param array $groupsDTO
     * @return \ArrayAccess
     */
    public function create(\ArrayAccess $groups, array $groupsDTO)
    {
        foreach ($groupsDTO as $groupDTO) {
            $groups->add(new Group($groupDTO->name));
        }
        return $groups;
    }
}
