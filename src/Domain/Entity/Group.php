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
     * @inheritdoc
     */
    public function __construct(
        string $name
    ) {
        $this->id = Uuid::uuid4();
        $this->tricks = new ArrayCollection();

        $this->name = $name;
    }

    /**
     * @inheritdoc
     */
    public function addTrick(TrickInterface $trick): void
    {
        if (!$this->tricks->contains($trick)) {
            $this->tricks->add($trick);
        }
    }

    /**
     * @inheritdoc
     */
    public function removeTrick(TrickInterface $trick): void
    {
        if ($this->tricks->contains($trick)) {
            $this->tricks->removeElement($trick);
        }
    }

    /**
     * @inheritdoc
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritdoc
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
