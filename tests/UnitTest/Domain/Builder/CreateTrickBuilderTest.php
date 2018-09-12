<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\Domain\Builder;

use App\Domain\Builder\CreateTrickBuilder;
use App\Domain\Builder\Interfaces\CreateTrickBuilderInterface;
use App\Domain\Builder\Interfaces\GroupBuilderInterface;
use App\Domain\Builder\Interfaces\PictureBuilderInterface;
use App\Domain\Builder\Interfaces\VideoBuilderInterface;
use App\Domain\DTO\Interfaces\PictureDTOInterface;
use App\Domain\DTO\Interfaces\TrickDTOInterface;
use App\Domain\DTO\TrickDTO;
use App\Domain\Entity\Interfaces\GroupInterface;
use App\Domain\Entity\Interfaces\PictureInterface;
use App\Domain\Entity\Interfaces\TrickInterface;
use App\Domain\Entity\Interfaces\VideoInterface;
use App\Service\Image\Interfaces\ImageUploadWarmerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class CreateTrickBuilderTest extends TestCase
{
    /**
     * @var PictureBuilderInterface
     */
    private $pictureBuilder;

    /**
     * @var VideoBuilderInterface
     */
    private $videoBuilder;

    /**
     * @var GroupBuilderInterface
     */
    private $groupBuilder;

    /**
     * @var ImageUploadWarmerInterface
     */
    private $imageUploadWarmer;

    /**
     * @var UserInterface
     */
    private $user;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;


    public function setUp()
    {
        $this->pictureBuilder = $this->createMock(PictureBuilderInterface::class);
        $this->videoBuilder = $this->createMock(VideoBuilderInterface::class);
        $this->groupBuilder = $this->createMock(GroupBuilderInterface::class);
        $this->imageUploadWarmer = $this->createMock(ImageUploadWarmerInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);

        $token = $this->createMock(TokenInterface::class);
        $this->user = $this->createMock(UserInterface::class);

        $this->tokenStorage->method('getToken')->willReturn($token);
        $token->method('getUser')->willReturn($this->user);
    }

    private function constructInstance()
    {
        return new CreateTrickBuilder(
            $this->pictureBuilder,
            $this->videoBuilder,
            $this->groupBuilder,
            $this->imageUploadWarmer,
            $this->tokenStorage
        );
    }

    public function testConstruct()
    {
        $createTrickBuilder = $this->constructInstance();

        static::assertInstanceOf(CreateTrickBuilderInterface::class, $createTrickBuilder);
    }


    /**
     * @param TrickDTOInterface $trickDTO
     *
     * @dataProvider provideData
     */
    public function testCreateFunction(TrickDTOInterface $trickDTO)
    {
        // Create the stubs

        $mainPicture = $this->createMock(PictureInterface::class);

        $pictures = [
            $this->createMock(PictureInterface::class),
            $this->createMock(PictureInterface::class),
            $this->createMock(PictureInterface::class)
        ];

        $videos = [
            $this->createMock(VideoInterface::class),
            $this->createMock(VideoInterface::class),
        ];

        $groups = new ArrayCollection();
        $groups->add($this->createMock(GroupInterface::class));
        $groups->add($this->createMock(GroupInterface::class));


        $this->pictureBuilder->expects($this->at(0))->method('create')->willReturn($mainPicture);

        $this->pictureBuilder->expects($this->at(1))->method('create')->willReturn($pictures);

        $this->videoBuilder->method('create')->willReturn($videos);

        $this->groupBuilder->method('create')->willReturn($groups);


        // Executing

        $createTrickBuilder = $createTrickBuilder = $this->constructInstance();

        $trick = $createTrickBuilder->create($trickDTO);


        // Assertions

        static::assertInstanceOf(TrickInterface::class, $trick);
        static::assertSame($trickDTO->title, $trick->getTitle());
        static::assertSame($trickDTO->description, $trick->getDescription());
        static::assertSame($mainPicture, $trick->getMainPicture());

        static::assertInstanceOf(\ArrayAccess::class, $trick->getPictures());
        static::assertCount(sizeof($pictures), $trick->getPictures());
        foreach ($pictures as $key => $picture) {
            static::assertSame($picture, $trick->getPictures()->get($key));
        }

        static::assertInstanceOf(\ArrayAccess::class, $trick->getVideos());
        static::assertCount(sizeof($videos), $trick->getVideos());
        foreach ($videos as $key => $video) {
            static::assertSame($video, $trick->getVideos()->get($key));
        }

        static::assertInstanceOf(\ArrayAccess::class, $trick->getGroups());
        static::assertCount(sizeof($groups), $trick->getGroups());
        foreach ($groups as $key => $group) {
            static::assertSame($group, $trick->getGroups()->get($key));
        }
    }

    public function provideData()
    {
        // Build the TrickDTO

        $mainPictureDTO = $this->createMock(PictureDTOInterface::class);

        $trickDTO = new TrickDTO(
            'Title',
            'Description',
            $mainPictureDTO,
            array(),
            array(),
            new ArrayCollection(),
            []
        );


        yield [$trickDTO];
    }
}
