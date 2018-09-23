<?php
declare(strict_types=1);

namespace App\Tests\UnitTest\Domain\Builder;

use App\Domain\Builder\Interfaces\GroupBuilderInterface;
use App\Domain\Builder\Interfaces\PictureBuilderInterface;
use App\Domain\Builder\Interfaces\UpdateTrickBuilderInterface;
use App\Domain\Builder\UpdateTrickBuilder;
use App\Domain\DTO\Interfaces\GroupDTOInterface;
use App\Domain\DTO\Interfaces\TrickDTOInterface;
use App\Domain\DTO\Interfaces\VideoDTOInterface;
use App\Domain\DTO\PictureDTO;
use App\Domain\DTO\TrickDTO;
use App\Domain\Entity\Interfaces\GroupInterface;
use App\Domain\Entity\Interfaces\PictureInterface;
use App\Domain\Entity\Interfaces\TrickInterface;
use App\Domain\Entity\Interfaces\VideoInterface;
use App\Domain\Entity\Picture;
use App\Domain\Entity\Trick;
use App\Service\CollectionManager\Interfaces\CollectionUpdatePrepareInterface;
use App\Service\Image\Interfaces\FolderChangerInterface;
use App\Service\Image\Interfaces\ImageRemoverInterface;
use App\Service\Image\Interfaces\ImageUploadWarmerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;

final class UpdateTrickBuilderTest extends TestCase
{
    /**
     * @var CollectionUpdatePrepareInterface
     */
    private $collectionPrepare;

    /**
     * @var ImageUploadWarmerInterface
     */
    private $imageUploadWarmer;

    /**
     * @var ImageRemoverInterface
     */
    private $imageRemover;

    /**
     * @var FolderChangerInterface
     */
    private $folderChanger;

    /**
     * @var GroupBuilderInterface
     */
    private $groupBuilder;

    /**
     * @var PictureBuilderInterface
     */
    private $pictureBuilder;

    public function setUp()
    {
        $this->collectionPrepare = $this->createMock(CollectionUpdatePrepareInterface::class);
        $this->imageUploadWarmer = $this->createMock(ImageUploadWarmerInterface::class);
        $this->imageRemover = $this->createMock(ImageRemoverInterface::class);
        $this->folderChanger = $this->createMock(FolderChangerInterface::class);
        $this->groupBuilder = $this->createMock(GroupBuilderInterface::class);
        $this->pictureBuilder = $this->createMock(PictureBuilderInterface::class);
    }

    private function constructInstance()
    {
        return new UpdateTrickBuilder(
            $this->collectionPrepare,
            $this->imageUploadWarmer,
            $this->imageRemover,
            $this->folderChanger,
            $this->groupBuilder,
            $this->pictureBuilder
        );
    }


    public function testConstruct()
    {
        $updateTrickBuilder = $this->constructInstance();

        static::assertInstanceOf(UpdateTrickBuilderInterface::class, $updateTrickBuilder);
    }


    /**
     * @param TrickInterface $trick
     * @param TrickDTOInterface $trickDTO
     *
     * @dataProvider provideData
     */
    public function testBuilderReturnGoodValues(TrickInterface $trick, TrickDTOInterface $trickDTO)
    {
        // Build the Stubs
        $this->imageUploadWarmer->method('initialize')->willReturn(null);
        $this->imageRemover->method('addFileToRemove')->willReturn(null);

        $mainPicture = $this->createMock(PictureInterface::class);
        $this->pictureBuilder->method('create')->willReturn($mainPicture);

        $pictureCollection = [
            $this->createMock(PictureInterface::class),
            $this->createMock(PictureInterface::class),
            $this->createMock(PictureInterface::class),
        ];
        $this->collectionPrepare->expects($this->at(0))->method('prepare')->willReturn($pictureCollection);

        $videoCollection = [
            $this->createMock(VideoInterface::class),
            $this->createMock(VideoInterface::class),
            $this->createMock(VideoInterface::class),
        ];
        $this->collectionPrepare->expects($this->at(1))->method('prepare')->willReturn($videoCollection);

        $groupCollection = new ArrayCollection();
        $groupCollection->add($this->createMock(GroupInterface::class));
        $groupCollection->add($this->createMock(GroupInterface::class));
        $this->groupBuilder->method('create')->willReturn($groupCollection);


        // Execute
        $updateTrickBuilder = $this->constructInstance();

        $trick = $updateTrickBuilder->update($trick, $trickDTO);


        // Assertions
        static::assertInstanceOf(TrickInterface::class, $trick);
        static::assertSame($trickDTO->title, $trick->getTitle());
        static::assertSame($trickDTO->description, $trick->getDescription());
        static::assertSame($mainPicture, $trick->getMainPicture());

        static::assertInstanceOf(\ArrayAccess::class, $trick->getPictures());
        static::assertCount(sizeof($pictureCollection), $trick->getPictures());
        foreach ($pictureCollection as $key => $picture) {
            static::assertSame($picture, $trick->getPictures()->get($key));
        }

        static::assertInstanceOf(\ArrayAccess::class, $trick->getVideos());
        static::assertCount(sizeof($videoCollection), $trick->getVideos());
        foreach ($videoCollection as $key => $video) {
            static::assertSame($video, $trick->getVideos()->get($key));
        }

        static::assertInstanceOf(\ArrayAccess::class, $trick->getGroups());
        static::assertCount(sizeof($groupCollection->toArray()), $trick->getGroups());
        foreach ($groupCollection->toArray() as $key => $group) {
            static::assertSame($group, $trick->getGroups()->get($key));
        }
    }


    /**
     * @throws \Exception
     */
    public function provideData()
    {
        // Build the Trick
        $user = $this->createMock(UserInterface::class);
        $mainPicture = new Picture(
            'path/to/file',
            '1.jpg',
            'image-1'
        );

        $pictureCollection = [
            $this->createMock(PictureInterface::class),
            $this->createMock(PictureInterface::class),
            $this->createMock(PictureInterface::class),
        ];

        $videoCollection = [
            $this->createMock(VideoInterface::class),
            $this->createMock(VideoInterface::class),
            $this->createMock(VideoInterface::class)
        ];

        $groupCollection = new ArrayCollection();
        $groupCollection->add($this->createMock(GroupInterface::class));
        $groupCollection->add($this->createMock(GroupInterface::class));

        $trick = new Trick(
            'Trick Title',
            'Trick description',
            $user,
            $mainPicture,
            $pictureCollection,
            $videoCollection,
            $groupCollection
        );


        // Build the TrickDTO

        $mainPictureDTO = new PictureDTO($this->createMock(UploadedFile::class));

        $pictureDTOCollection = [
            new PictureDTO($this->createMock(File::class)),
            new PictureDTO($this->createMock(File::class)),
        ];

        $videoDTOCollection = [
            $this->createMock(VideoDTOInterface::class),
            $this->createMock(VideoDTOInterface::class),
            $this->createMock(VideoDTOInterface::class),
            $this->createMock(VideoDTOInterface::class),
        ];

        $groupDTOCollection = [
            $this->createMock(GroupDTOInterface::class),
            $this->createMock(GroupDTOInterface::class),
            $this->createMock(GroupDTOInterface::class),
            $this->createMock(GroupDTOInterface::class),
        ];

        $trickDTO = new TrickDTO(
            'Trick Title',
            'New description',
            $mainPictureDTO,
            $pictureDTOCollection,
            $videoDTOCollection,
            $groupCollection,
            $groupDTOCollection
        );


        yield [$trick, $trickDTO];
    }
}
