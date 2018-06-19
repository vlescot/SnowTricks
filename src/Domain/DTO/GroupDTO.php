<?php

namespace App\Domain\DTO;

use App\Domain\Entity\Trick;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\UuidInterface;

/**
 * Class GroupDTO.
 */
class GroupDTO
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

    /**
     * GroupDTO constructor.
     */
    public function __construct()
    {
        $this->tricks = new ArrayCollection();
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @param UuidInterface $id
     */
    public function setId(UuidInterface $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param Trick $trick
     *
     * @return GroupDTO
     */
    public function addTrick(Trick $trick): self
    {
        if (!$this->tricks->contains($trick)) {
            $this->tricks[] = $trick;
        }

        return $this;
    }

    /**
     * @param Trick $trick
     *
     * @return GroupDTO
     */
    public function removeTrick(Trick $trick): self
    {
        if ($this->tricks->contains($trick)) {
            $this->tricks->removeElement($trick);
        }

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getTricks(): ArrayCollection
    {
        return $this->tricks;
    }
}
