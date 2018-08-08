<?php
declare(strict_types = 1);

namespace App\Domain\Entity;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class Video.
 */
class Video
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
     * @var Trick
     */
    private $trick;

    /**
     * Video constructor.
     *
     * @param string $iFrame
     * @throws \Exception
     */
    public function __construct(string $iFrame)
    {
        $this->id = Uuid::uuid4();
        $this->iFrame = $iFrame;
    }

    /**
     * @param string $iFrame
     */
    public function update(string $iFrame)
    {
        $this->iFrame = $iFrame;
    }

    /**
     * @param Trick $trick
     */
    public function setTrick(Trick $trick)
    {
        $this->trick = $trick;
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getIFrame(): ?string
    {
        return $this->iFrame;
    }
}
