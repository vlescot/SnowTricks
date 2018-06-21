<?php

namespace App\Domain\Entity;

use App\Domain\DTO\GroupDTO;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class Group.
 */
class Group
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \ArrayAccess
     */
    private $tricks;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->tricks = new ArrayCollection();
    }

    public function create(GroupDTO $groupDTO)
    {
        $this->name = $groupDTO->name;

        $tricks = $groupDTO->tricks;
        foreach ($tricks->getIterator() as $trick) {
            $this->addTrick($trick);
        }
    }

    public function addTrick(Trick $trick): self
    {
        if (!$this->tricks->contains($trick)) {
            $this->tricks[] = $trick;
            $trick->addGroup($this);
        }

        return $this;
    }

    public function removeTrick(Trick $trick): self
    {
        if ($this->tricks->contains($trick)) {
            $this->tricks->removeElement($trick);
            $trick->removeGroup($this);
        }

        return $this;
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return \ArrayAccess
     */
    public function getTricks(): \ArrayAccess
    {
        return $this->tricks;
    }
}
