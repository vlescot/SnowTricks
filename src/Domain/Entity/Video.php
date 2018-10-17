<?php
declare(strict_types = 1);

namespace App\Domain\Entity;

use App\Domain\Entity\Interfaces\TrickInterface;
use App\Domain\Entity\Interfaces\VideoInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Video implements VideoInterface
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var string
     */
    private $iFrame;

    /**
     * @var TrickInterface
     */
    private $trick;

    /**
     * @inheritdoc
     */
    public function __construct(string $iFrame)
    {
        $this->id = Uuid::uuid4();
        $this->iFrame = $iFrame;
    }

    /**
     * @inheritdoc
     */
    public function setTrick(TrickInterface $trick)
    {
        $this->trick = $trick;
    }

    /**
     * @inheritdoc
     */
    public function unsetTrick()
    {
        $this->trick = null;
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
    public function getIFrame(): string
    {
        return $this->iFrame;
    }

    /**
     * @inheritdoc
     */
    public function getTrick(): TrickInterface
    {
        return $this->trick;
    }
}
