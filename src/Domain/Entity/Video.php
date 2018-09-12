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
     * Video constructor.
     *
     * @param string $iFrame
     *
     * @throws \Exception
     */
    public function __construct(string $iFrame)
    {
        $this->id = Uuid::uuid4();
        $this->iFrame = $iFrame;
    }

    /**
     * @param TrickInterface $trick
     *
     * @return mixed|void
     */
    public function setTrick(TrickInterface $trick)
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
     * @return string
     */
    public function getIFrame(): string
    {
        return $this->iFrame;
    }

    /**
     * @return TrickInterface
     */
    public function getTrick(): TrickInterface
    {
        return $this->trick;
    }
}
