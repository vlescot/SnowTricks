<?php
declare(strict_types=1);

namespace Tests\UnitTest\Domain\Entity;

use App\Domain\Entity\Interfaces\CommentInterface;
use App\Domain\Entity\Interfaces\GroupInterface;
use App\Domain\Entity\Interfaces\PictureInterface;
use App\Domain\Entity\Interfaces\TrickInterface;
use App\Domain\Entity\Interfaces\VideoInterface;
use App\Domain\Entity\Trick;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class TrickTest extends TestCase
{
    /**
     * @param string $title
     * @param string $description
     * @param UserInterface $userMock
     * @param PictureInterface $mainPictureMock
     * @param array $picturesCollection
     * @param array $videosCollection
     * @param \ArrayAccess $groupsCollection
     *
     * @dataProvider provideTrickData
     */
    public function testCreateTrickHasGoodValue(
        string $title,
        string $description,
        UserInterface $userMock,
        PictureInterface$mainPictureMock,
        array $picturesCollection,
        array $videosCollection,
        \ArrayAccess $groupsCollection
    ) {
        $trick = new Trick(
            $title,
            $description,
            $userMock,
            $mainPictureMock,
            $picturesCollection,
            $videosCollection,
            $groupsCollection
        );


        static::assertInstanceOf(TrickInterface::class, $trick);
        static::assertInstanceOf(UuidInterface::class, $trick->getId());
        static::assertInternalType('string', $trick->getSlug());
        static::assertInternalType('int', $trick->getCreatedAt());
        static::assertSame($title, $trick->getTitle());
        static::assertSame($description, $trick->getDescription());
        static::assertSame($userMock, $trick->getAuthor());
        static::assertSame($mainPictureMock, $trick->getMainPicture());
        static::assertInstanceOf(\ArrayAccess::class, $trick->getPictures());
        foreach ($picturesCollection as $key => $picture) {
            static::assertSame($picture, $trick->getPictures()->get($key));
        }
        static::assertInstanceOf(\ArrayAccess::class, $trick->getVideos());
        foreach ($videosCollection as $key => $video) {
            static::assertSame($video, $trick->getVideos()->get($key));
        }
        static::assertInstanceOf(\ArrayAccess::class, $trick->getGroups());
        foreach ($groupsCollection as $key => $group) {
            static::assertSame($group, $trick->getGroups()->get($key));
        }
    }

    /**
     * @param string $title
     * @param string $description
     * @param UserInterface $userMock
     * @param PictureInterface $mainPictureMock
     * @param array $picturesCollection
     * @param array $videosCollection
     * @param \ArrayAccess $groupsCollection
     *
     * @dataProvider provideTrickData
     */
    public function testUpdateTrickHasGoodValue(
        string $title,
        string $description,
        UserInterface $userMock,
        PictureInterface$mainPictureMock,
        array $picturesCollection,
        array $videosCollection,
        \ArrayAccess $groupsCollection
    ) {
        $trick = new Trick(
            $title,
            $description,
            $userMock,
            $mainPictureMock,
            $picturesCollection,
            $videosCollection,
            $groupsCollection
        );

        // Update Trick
        $newTitle = 'NewTitle';
        $newDescription = 'NewDescription';
        $newMainPictureMock = $this->createMock(PictureInterface::class);
        $newPicturesCollection = [
            $this->createMock(PictureInterface::class),
            $this->createMock(PictureInterface::class),
            $this->createMock(PictureInterface::class),
            $this->createMock(PictureInterface::class)
        ];
        $newVideosCollection = [
            $this->createMock(VideoInterface::class),
            $this->createMock(VideoInterface::class)
        ];
        $newGroupsCollection = new ArrayCollection();
        $newGroupsCollection->add($this->createMock(GroupInterface::class));

        $trick->update(
            $newTitle,
            $newDescription,
            $newMainPictureMock,
            $newPicturesCollection,
            $newVideosCollection,
            $newGroupsCollection
        );


        static::assertInternalType('int', $trick->getUpdatedAt());
        static::assertSame($newTitle, $trick->getTitle());
        static::assertSame($newDescription, $trick->getDescription());
        static::assertSame($newMainPictureMock, $trick->getMainPicture());
        static::assertInstanceOf(\ArrayAccess::class, $trick->getPictures());
        foreach ($newPicturesCollection as $key => $picture) {
            static::assertSame($picture, $trick->getPictures()->get($key));
        }
        static::assertInstanceOf(\ArrayAccess::class, $trick->getVideos());
        foreach ($newVideosCollection as $key => $video) {
            static::assertSame($video, $trick->getVideos()->get($key));
        }
        static::assertInstanceOf(\ArrayAccess::class, $trick->getGroups());
        foreach ($newGroupsCollection as $key => $group) {
            static::assertSame($group, $trick->getGroups()->get($key));
        }
    }

    /**
     * @param string $title
     * @param string $description
     * @param UserInterface $userMock
     * @param PictureInterface $mainPictureMock
     * @param array $picturesCollection
     * @param array $videosCollection
     * @param \ArrayAccess $groupsCollection
     *
     * @dataProvider provideTrickData
     */
    public function testAddCommentProvideGoodData(
        string $title,
        string $description,
        UserInterface $userMock,
        PictureInterface$mainPictureMock,
        array $picturesCollection,
        array $videosCollection,
        \ArrayAccess $groupsCollection
    ) {
        $trick = new Trick(
            $title,
            $description,
            $userMock,
            $mainPictureMock,
            $picturesCollection,
            $videosCollection,
            $groupsCollection
        );

        // Add a Comment
        $commentMock1 = $this->createMock(CommentInterface::class);
        $commentMock2 = $this->createMock(CommentInterface::class);

        $trick->addComment($commentMock1);
        $trick->addComment($commentMock2);

        static::assertInstanceOf(\ArrayAccess::class, $trick->getComments());
        static::assertSame($commentMock1, $trick->getComments()->first());
        static::assertSame($commentMock2, $trick->getComments()->next());
    }


    public function provideTrickData()
    {
        $title = 'Title';
        $description = 'Description';
        $userMock = $this->createMock(UserInterface::class);
        $mainPictureMock = $this->createMock(PictureInterface::class);
        $picturesCollection = [
            $this->createMock(PictureInterface::class),
            $this->createMock(PictureInterface::class),
            $this->createMock(PictureInterface::class),
        ];
        $videosCollection = [
            $this->createMock(VideoInterface::class),
            $this->createMock(VideoInterface::class),
            $this->createMock(VideoInterface::class),
        ];
        $groupsCollection = new ArrayCollection();
        $groupsCollection->add($this->createMock(GroupInterface::class));
        $groupsCollection->add($this->createMock(GroupInterface::class));


        yield [
            $title,
            $description,
            $userMock,
            $mainPictureMock,
            $picturesCollection,
            $videosCollection,
            $groupsCollection
        ];
    }
}
