<?php
declare(strict_types = 1);

namespace App\Domain\Builder;

use App\Domain\Builder\Interfaces\GroupBuilderInterface;
use App\Domain\Entity\Group;

final class GroupBuilder implements GroupBuilderInterface
{
    /**
     * @param \ArrayAccess $groups
     * @param array $groupsDTO
     *
     * @return \ArrayAccess
     *
     * @throws \Exception
     */
    public function create(\ArrayAccess $groups, array $groupsDTO): \ArrayAccess
    {
        foreach ($groupsDTO as $groupDTO) {
            $groups->add(new Group($groupDTO->name));
        }
        return $groups;
    }
}
