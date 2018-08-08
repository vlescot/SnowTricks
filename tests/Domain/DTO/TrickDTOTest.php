<?php
declare(strict_types=1);

namespace App\Tests\Domain\DTO;

use App\Domain\DTO\GroupDTO;
use App\Domain\DTO\PictureDTO;
use App\Domain\DTO\UserDTO;
use App\Domain\DTO\VideoDTO;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use PHPUnit\Framework\TestCase;
use App\Domain\DTO\TrickDTO;

class TrickDTOTest extends TestCase
{
    /**
     * @var TrickDTO
     */
    private $dto;

    public function setUp()
    {
        $pictureMock = $this->createMock(PictureDTO::class);
        $collectionMock = $this->createMock(ArrayCollection::class);

        $this->dto = new TrickDTO(
            'A Title',
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut luctus elit vitae tortor rutrum, at dignissim libero finibus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.',
            $pictureMock,
            [],
            [],
            $collectionMock,
            []
        );
    }

    public function testTrickAttributesMustBeValid()
    {
        static::assertInternalType('string', $this->dto->title);
        static::assertInternalType('string', $this->dto->description);
        static::assertInstanceOf(PictureDTO::class, $this->dto->mainPicture);
        static::assertInternalType('array', $this->dto->pictures);
        static::assertInternalType('array', $this->dto->videos);
        static::assertInstanceOf(\ArrayAccess::class, $this->dto->groups);
        static::assertInternalType('array', $this->dto->newGroups);
    }
}
