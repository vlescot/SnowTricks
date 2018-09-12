<?php
declare(strict_types = 1);

namespace App\Domain\Entity;

use App\Domain\Entity\Interfaces\GroupInterface;
use App\Domain\Entity\Interfaces\TrickInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Group implements GroupInterface
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

    /**
     * Group constructor.
     *
     * @param string $name
     * @throws \Exception
     */
    public function __construct(
        string $name
    ) {
        $this->id = Uuid::uuid4();
        $this->tricks = new ArrayCollection();

        $this->name = $name;
    }

    /**
     * @param TrickInterface $trick
     */
    public function addTrick(TrickInterface $trick): void
    {
        if (!$this->tricks->contains($trick)) {
            $this->tricks->add($trick);
        }
    }

    /**
     * @param TrickInterface $trick
     */
    public function removeTrick(TrickInterface $trick): void
    {
        if ($this->tricks->contains($trick)) {
            $this->tricks->removeElement($trick);
        }
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

    public function __toString()
    {
        return $this->name;
    }
}
