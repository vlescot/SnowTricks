<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\Domain\Factory;

use App\Domain\DTO\Interfaces\PictureDTOInterface;
use App\Domain\DTO\Interfaces\TrickDTOInterface;
use App\Domain\DTO\Interfaces\VideoDTOInterface;
use App\Domain\Entity\Interfaces\GroupInterface;
use App\Domain\Entity\Interfaces\PictureInterface;
use App\Domain\Entity\Interfaces\VideoInterface;
use App\Domain\Entity\Trick;
use App\Domain\Factory\Interfaces\PictureDTOFactoryInterface;
use App\Domain\Factory\Interfaces\VideoDTOFactoryInterface;
use App\Domain\Factory\PictureDTOFactory;
use App\Domain\Factory\TrickDTOFactory;
use App\Domain\Factory\VideoDTOFactory;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\User\UserInterface;

final class TrickDTOFactoryTest extends TestCase
{
    /**
     * @var PictureDTOFactory
     */
    private $pictureDTOFactory;

    /**
     * @var VideoDTOFactory
     */
    private $videoDTOFactory;


    public function setUp()
    {
        $this->pictureDTOFactory = $this->createMock(PictureDTOFactoryInterface::class);
        $pictureDTO = $this->createMock(PictureDTOInterface::class);
        $this->pictureDTOFactory->method('create')->willReturn($pictureDTO);

        $this->videoDTOFactory = $this->createMock(VideoDTOFactoryInterface::class);
        $videoDTO = $this->createMock(VideoDTOInterface::class);
        $this->videoDTOFactory->method('create')->willReturn($videoDTO);
    }

    public function testConstruct()
    {
        $trickDTOFactory = new TrickDTOFactory(
            $this->pictureDTOFactory,
            $this->videoDTOFactory
        );

        static::assertInstanceOf(TrickDTOFactory::class, $trickDTOFactory);
    }

    public function testReturnGoodValue()
    {
        $user = $this->createMock(UserInterface::class);
        $mainPicture = $this->createMock(PictureInterface::class);

        $pictures = [
            $this->createMock(PictureInterface::class),
            $this->createMock(PictureInterface::class),
            $this->createMock(PictureInterface::class),
            $this->createMock(PictureInterface::class)
        ];

        $videos = [
            $this->createMock(VideoInterface::class),
            $this->createMock(VideoInterface::class),
            $this->createMock(VideoInterface::class)
        ];

        $groups = new ArrayCollection();
        $groups->add($this->createMock(GroupInterface::class));
        $groups->add($this->createMock(GroupInterface::class));


        $trick = new Trick(
            'title',
            'description',
            $user,
            $mainPicture,
            $pictures,
            $videos,
            $groups
        );



        $trickDTOFactory = new TrickDTOFactory(
            $this->pictureDTOFactory,
            $this->videoDTOFactory
        );

        $trickDTO = $trickDTOFactory->create($trick);

        static::assertInstanceOf(TrickDTOInterface::class, $trickDTO);
    }
}
