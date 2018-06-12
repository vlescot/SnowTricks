<?php

namespace App\Domain\Entity;

use App\Domain\DTO\GroupDTO;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\UuidInterface;

/**
 * Class Group
 * @package App\Domain\Entity
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
     * @var ArrayCollection
     */
    private $tricks;

    public function __construct()
    {
        $this->tricks = new ArrayCollection();
    }

    public function creation(GroupDTO $groupDTO)
    {
        $this->name = $groupDTO->getName();

        $tricks = $groupDTO->getTricks();
        foreach ($tricks->getIterator() as $trick){
            $this->addTrick($trick);
        }
    }

    private function addTrick(Trick $trick)
    {
        if (!$this->tricks->contains($trick)) {
            $this->tricks[] = $trick;
        }

        return $this;
    }

    private function removeTrick(Trick $trick): self
    {
        if ($this->tricks->contains($trick)) {
            $this->tricks->removeElement($trick);
        }

        return $this;
    }
}