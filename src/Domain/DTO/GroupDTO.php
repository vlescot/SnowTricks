<?php

namespace App\Domain\DTO;

use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\UuidInterface;

/**
 * Class GroupDTO
 * @package App\Domain\DTO
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
     * @param TrickDTO $trick
     * @return GroupDTO
     */
    public function addTrick(TrickDTO $trick): self
    {
        if (!$this->tricks->contains($trick)) {
            $this->tricks[] = $trick;
            $trick->addGroup($this);
        }

        return $this;
    }

    /**
     * @param TrickDTO $trick
     * @return GroupDTO
     */
    public function removeTrick(TrickDTO $trick): self
    {
        if ($this->tricks->contains($trick)) {
            $this->tricks->removeElement($trick);
            $trick->removeGroup($this);
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